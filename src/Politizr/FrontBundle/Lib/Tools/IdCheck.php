<?php
namespace Politizr\FrontBundle\Lib\Tools;

use Symfony\Component\HttpFoundation\Request;

use Politizr\Constant\IdCheckConstants;

use Politizr\Model\PUser;

/**
 * IdChek.io useful methods WSDL
 *
 * @author Lionel Bouzonville
 */
class IdCheck
{
    private $securityTokenStorage;
    private $logger;

    private $wsdlUrl;
    private $wsdlLogin;
    private $wsdlPassword;

    private $wsdlParams = array (
        'encoding' => 'UTF-8',
        'verifypeer' => false,
        'verifyhost' => false,
        'soap_version' => SOAP_1_2,
        'trace' => 1,
        'exceptions' => 1,
        'connection_timeout' => 180,
        'authentication' => 'SOAP_AUTHENTICATION_BASIC',
    );
    private $wsdlClient;

    private $lastResult;

    private $errorLevel;
    private $errorMsg;

    /**
     *
     * @param @security.token_storage
     * @param %idcheck_wsdl_url%
     * @param %idcheck_login%
     * @param %idcheck_password%
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $wsdlUrl,
        $login,
        $password,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;

        $this->wsdlUrl = $wsdlUrl;
        $this->wsdlLogin = $login;
        $this->wsdlPassword = $password;

        $this->wsdlParams['login'] = $login;
        $this->wsdlParams['password'] = $password;

        $this->logger = $logger;

        $this->wsdlClient = new \SoapClient(
            $this->wsdlUrl,
            $this->wsdlParams
        );

        $this->errorMsg = array();
    }

    /**
     *
     * @param array
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     *
     * @param array
     */
    public function getErrorLevel()
    {
        return $this->errorLevel;
    }

    /**
     * Check zla and return result
     *
     * @param string $zla1
     * @param string $zla2
     * @return string WSDL constant response
     */
    public function executeZlaChecking($zla1, $zla2)
    {
        $this->logger->info('*** executeZlaChecking');
        $this->logger->info('$zla1 = '.print_r($zla1, true));
        $this->logger->info('$zla2 = '.print_r($zla2, true));

        $this->errorMsg = array();

        $now = new \DateTime();
        // expected format example: 2016-04-15T13:55:14.179+02:00
        $dateFormat = $now->format('Y-m-d\TH:i:s.\0\0\0P');

        $optionsMzr = array(
            "CheckMrz" => array (
                "requestDate" => $dateFormat,
                "accountUID" => $this->wsdlLogin,
                "userID" => $this->wsdlLogin,
                "locale" => "FR",
                "version" => "1.2.0",
                "authenticationInfo" => array (
                    "password" => $this->wsdlPassword
                ),
                "mrz" => array (
                    "line1" => $zla1,
                    "line2" => $zla2,
                ),
                "getPdfReport" => array (
                   "getPdfReport" => true
                )
            )
        );

        $this->lastResult = $this->wsdlClient->__soapCall("CheckMrz", $optionsMzr);
        $this->logger->info(print_r($this->lastResult, true));

        if (isset($this->lastResult) && isset($this->lastResult->contentOk) && isset($this->lastResult->contentOk->result) && $this->lastResult->contentOk->result == IdCheckConstants::WSDL_RESULT_OK) {
            return true;
        } else {
            $this->errorMsg[] = 'Erreur de saisie, merci de vérifier les informations fournies.';
            $this->errorLevel = IdCheckConstants::ERROR_LEVEL_SOFT;
            return false;
        }
    }

    /**
     * Check image card id and return result
     *
     * @param string $rawImg
     * @return string WSDL constant response
     */
    public function executeImageIdCardChecking($rawImg)
    {
        $this->logger->info('*** executeImageIdCardChecking');

        $this->errorMsg = array();

        $now = new \DateTime();
        // expected format example: 2016-04-15T13:55:14.179+02:00
        $dateFormat = $now->format('Y-m-d\TH:i:s.\0\0\0P');

        $optionsImage = array(
            "CheckImageRequest" => array (
                "requestDate" => $dateFormat,
                "accountUID" => $this->wsdlLogin,
                "userID" => $this->wsdlLogin,
                "locale" => "FR",
                "version" => "1.2.0",
                "authenticationInfo" => array (
                    "password" => $this->wsdlPassword
                ),
                "image" => array (
                    "type" => 'RECTO',
                    "light" => 'DL',
                    "source" => 'SMARTPHONE',
                    "image" => $rawImg
                ),
                "getPdfReport" => array (
                   "getPdfReport" => true
                )
            )
        );

        $this->lastResult = $this->wsdlClient->__soapCall("CheckImage", $optionsImage);
        $this->logger->info(print_r($this->lastResult, true));
        // dump($this->lastResult);

        if (isset($this->lastResult) && isset($this->lastResult->contentOk) && isset($this->lastResult->contentOk->result) && $this->lastResult->contentOk->result == IdCheckConstants::WSDL_RESULT_OK) {
            return true;
        } else {
            $this->errorMsg[] = 'Nous n\'avons pas pu valider votre photo d\'identité: elle doit être nette et dans le sens de la lecture.';
            $this->errorLevel = IdCheckConstants::ERROR_LEVEL_SOFT;
            return false;
        }
    }

    /**
     * Compare last result with given user
     *
     * @param PUser $user
     * @return boolean true if gender & name & birthdate match
     */
    public function isUserLastResult(PUser $user)
    {
        $this->logger->info('*** isUserLastResult');
        $this->logger->info('$user = '.print_r($user, true));

        $this->errorMsg = array();

        if (! (isset($this->lastResult) && isset($this->lastResult->contentOk) && isset($this->lastResult->contentOk->result) && isset($this->lastResult->contentOk->holderDetail) && isset($this->lastResult->contentOk->holderDetail->gender) && isset($this->lastResult->contentOk->holderDetail->lastName) && isset($this->lastResult->contentOk->holderDetail->birthDate))) {
            return false;
        } else {
            $gender = $this->lastResult->contentOk->holderDetail->gender;
            $lastName = $this->lastResult->contentOk->holderDetail->lastName;

            if (is_array($lastName)) {
                $lastName = implode(' ', $lastName);
            }

            $lastName = strtoupper($lastName);
            $lastName = preg_replace('/\s+/', '-', $lastName);

            // $firstName = $this->lastResult->contentOk->holderDetail->firstName;
            $birthDate = $this->lastResult->contentOk->holderDetail->birthDate;
            // $rawBirthDate = $this->lastResult->contentOk->holderDetail->rawBirthDate;
            $birthDate = new \DateTime($birthDate);
            $birthDate = $birthDate->format('Y-m-d');

            $userGender = $user->getGender();
            $userLastName = strtoupper($user->getName());
            $userLastName = preg_replace('/\s+/', '-', $userLastName);

            // $userFirstName = $user->getFirstname();
            $userBirthDate = $user->getBirthday()->format('Y-m-d');

            if (!(($gender == 'M' && $userGender == 'Monsieur') || ($gender == 'F' && $userGender == 'Madame'))) {
                $this->logger->info('gender ko');

                $this->errorMsg[] = 'La civilité saisie ne correspond pas aux informations de la carte d\'identité.';
            }
            if (!($lastName == $userLastName)) {
                $this->logger->info('lastname ko');

                $this->errorMsg[] = 'Le nom saisi ne correspond pas aux informations de la carte d\'identité.';
            }
            if (!($birthDate == $userBirthDate)) {
                $this->logger->info('birthdate ko');

                $this->errorMsg[] = 'La date de naissance saisie ne correspond pas aux informations de la carte d\'identité.';
            }

            if (!empty($this->errorMsg)) {
                $this->errorLevel = IdCheckConstants::ERROR_LEVEL_REDIRECT;
                return false;
            }

            return true;
        }
    }
}
