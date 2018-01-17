# Insertion du propriétaire du groupe
INSERT INTO `p_c_owner` (`id`, `uuid`, `title`, `summary`, `description`, `created_at`, `updated_at`, `slug`)
VALUES ('1', NULL, 'Ariège le Département', '<p>Ariège le Département</p>', '<p>Ariège le Département</p>', now(), now(), 'ariege-le-departement');

# app/console politizr:uuids:populate PCOwner

# Insertion du groupe
INSERT INTO `p_circle` (`id`, `p_c_owner_id`, `uuid`, `title`, `summary`, `description`, `url`, `online`, `only_elected`, `created_at`, `updated_at`, `slug`, `sortable_rank`)
VALUES ('1', 1, NULL, 'Les Rencontres de l\'Ariège 2017', '<p>Les Rencontres de l\'Ariège 2017</p>', '
                    <div class="grpGlobalIntro">
                        <div class="grpGlobalIntroBigArticle">
                            <img src="/bundles/politizrfront/images/grp-CD09/grp-bigArticle.png">
                            <div class="grpGlobalIntroOneCol">
                                <h1>Quelques rappels utiles</h1>
                                <p><b>• Endettement du Département de l’Ariège...</b> 31 €/hab. (moyenne nationale à 535 €) : 2e Département le moins endetté de France.<br>
                                    <b>• Un budget 2018 contraint...</b> sur une hausse maximale de 1,4 % de son fonctionnement malgré une inflation déjà à 1 %.<br>
                                    • Des ambitions néanmoins assumées... <b>le déploiement du Très haut Débit, la création de la Légumerie centrale, le projet du Château de Foix, le lancement de l’Agence Ariège Attractivité, la sécurisation du lac de Montbel, etc.</b>
                                    <a class="grpKnowMore" target="_blank" href="http://www.ariege.fr/Actualites/Rencontrez-et-echangez-avec-vos-conseillers-departementaux">En savoir plus sur le site du Département</a>
                                </p>
                            </div>
                        </div>
                        <div class="grpGlobalIntroSmallArticles">
                            <div class="grpGlobalIntroOneCol">
                                <img src="/bundles/politizrfront/images/grp-CD09/grp_article1.jpg">
                                <h1>6 villes, 6 dates, 6 réunions publiques et citoyennes</h1>
                                <p>Le Président du Conseil Départemental et ses six Vice-Présidents donnent rendez-vous aux Ariégeoises et aux </p>
                            </div>
                            <div class="grpGlobalIntroOneCol">
                                <p>Ariégeois dans le cadre des Rencontres de l’Ariège.<br><br>
                                Ce 3ème rendez-vous avec la population articulé autour de 6 réunions publiques permet de présenter le <b>Débat d\'Orientations Budgétaires 2018</b>, étape importante qui donne le rythme et témoigne des ambitions du Conseil départemental pour l\'année à venir.
                                </p>
                            </div>
                            <div class="grpGlobalIntroTwoCol">
                                <img src="/bundles/politizrfront/images/grp-CD09/grp_article2.jpg">
                                <h1>Exprimez-vous et poursuivons le dialogue!</h1>
                                <p>En complément à ces 6 réunions publiques, nous mettons à votre disposition cette plateforme pour recueillir <b>vos avis et vos points de vue sur les orientations et les priorités du Conseil départemental pour l\'année à venir</b>.<br>
                                En fonction des questions et remarques, le Président du Conseil Départemental et ses six Vice-Présidents vous répondrons selon leurs domaines de compétences.</p>
                            </div>
                        </div>
                        <div class="grpTwitterFeed">
                            <div class="grpTwitterLogo">
                                <i class="icon-social-tw"></i><br>
                                Mentions<br>
                                <span>#Rencontresdelariege</span>
                            </div>
                            <a class="twitter-timeline" href="https://twitter.com/search?q=Rencontresdelariege" data-widget-id="937601123116036096" data-chrome="nofooter noheader noborders noscrollbar transparent" data-width="180" data-height="680">></a> 
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </div>
', NULL, '1', '0', now(), now(), 'les-rencontres-de-l-ariege-2017', 1);

# app/console politizr:uuids:populate PCircle

# Insertion de la charte du groupe
INSERT INTO `p_m_charte` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (2, 1, 'Charte des Rencontres de l\'Ariège sur Politizr', 'v1.0','
<h1>1 Pr&eacute;ambule</h1>
<p>En compl&eacute;ment de sa plate-forme g&eacute;n&eacute;rale, POLITIZR a con&ccedil;u et d&eacute;velopp&eacute; des espaces de discussion permettant aux collectivit&eacute;s de discuter avec leurs administr&eacute;s.</p>
<p>Cette charte s\'applique pour la collectivit&eacute; "Ari&egrave;ge le d&eacute;partement" au sujet du groupe "Les Rencontres de l\'Ariège 2017": elle a &eacute;t&eacute; r&eacute;dig&eacute;e conjointement par POLITIZR et le Conseil D&eacute;partemental de l\'Ari&egrave;ge.</p>
<p>Afin de garantir la qualit&eacute; des discussions, toute contribution &agrave; la plate-forme est subordonn&eacute;e au respect de la pr&eacute;sente Charte.</p>
<h1>2 Respect et convivialit&eacute;</h1>
<p>Les termes de toute contribution doivent &ecirc;tre courtois et mesur&eacute;s.</p>
<p>Tout contributeur s&rsquo;engage au respect des autres. Ainsi, toute invective, insulte, injure, d&eacute;nigrement, malveillance, harc&egrave;lement est prohib&eacute; tant envers tout autre utilisateur, tous tiers, le Conseil D&eacute;partemental de l\'Ari&egrave;ge et POLITIZR.</p>
<p>Les contributeurs s&rsquo;interdisent de divulguer des informations relevant de la vie priv&eacute;e d&rsquo;autres contributeurs ou de toute autre personne.</p>
<p>Les utilisateurs s&rsquo;interdisent d\'induire en erreur d\'autres utilisateurs en usurpant l\'identit&eacute; ou une d&eacute;nomination sociale ou en portant atteinte &agrave; l\'image ou &agrave; la r&eacute;putation d\'autres personnes et/ou en se faisant passer pour un tiers ou pour un employ&eacute;, un service habilit&eacute; ou un affili&eacute; du&nbsp;Conseil D&eacute;partemental de l\'Ari&egrave;ge ou de POLITIZR.</p>
<h1>3 Argumentation de qualit&eacute;</h1>
<p>Les contributions doivent avoir un lien pertinent avec les th&eacute;matiques propos&eacute;es par&nbsp;le Conseil D&eacute;partemental de l\'Ari&egrave;ge.</p>
<p>Dans la mesure du possibile, il n&rsquo;y aura pas lieu &agrave; la publication de plusieurs contributions identiques ou similaires.</p>
<p>L&rsquo;utilisation des majuscules ou des caract&egrave;res r&eacute;p&eacute;t&eacute;s pour renforcer les arguments n&rsquo;est pas admise.</p>
<h1>4 Mod&eacute;ration</h1>
<p>POLITIZR pratique une mod&eacute;ration a posteriori et non syst&eacute;matique de l&rsquo;ensemble des contributions et informations associ&eacute;es&nbsp;aux profils.</p>
<p>Toute personne ayant connaissance d&rsquo;un contenu illicite ou contraire &agrave; la pr&eacute;sente charte est invit&eacute;e &agrave; en informer POLITZR en remplissant le formulaire de signalement pr&eacute;sent sur l&rsquo;ensemble des publications (icone &lt;!&gt;).</p>
<p>POLITIZR est autoris&eacute; &agrave; supprimer du site de son propre chef ou suite &agrave; un signalement toute contribution totalement ou partiellement notamment aux fins de respect des tiers, de la Charte &eacute;thique et du bon fonctionnement du site.</p>
<p>Sont notamment soumis a mod&eacute;ration :</p>
<ul>
<li>Toute publicit&eacute; ou lien promotionnel vers un ou des sites ext&eacute;rieurs ne sont pas autoris&eacute;s. Toute publicit&eacute; ou promotion contenues dans une contribution sont interdites.</li>
<li>les petites annonces et autres publications sans rapport avec la politique.</li>
<li>les contributions &agrave; caract&egrave;re raciste, x&eacute;nophobe, r&eacute;visionniste, n&eacute;gationniste;&nbsp;</li>
<li>les propos injurieux, diffamatoires, discriminants, envers une personne ou un groupe de personnes,</li>
</ul>
<ol>
<li>en raison de leur origine, de leur appartenance ou de leur non-appartenance, vraie ou suppos&eacute;e, &agrave; une ethnie, une nation, ou une religion;</li>
<li>en raison de leur sexe, de leur orientation sexuelle ou de leur handicap;</li>
</ol>
<ul>
<li>les propos injurieux, diffamatoires, discriminants, portant atteinte &agrave; la vie priv&eacute;e, au droit &agrave; l\'image, ou &agrave; la r&eacute;putation et aux droits d&rsquo;autrui . Et plus g&eacute;n&eacute;ralement, toute violation des droits de propri&eacute;t&eacute; intellectuelle (notamment en mati&egrave;re de musique, vid&eacute;o, animations, jeux, logiciels, bases de donn&eacute;es, images, sons et textes), tout autre droit de propri&eacute;t&eacute; appartenant &agrave; un tiers ou tout secret commercial appartenant &agrave; un de ces tiers;</li>
<li>les propos portant atteinte &agrave; la dignit&eacute; humaine ;</li>
<li>la provocation &agrave; la violence, au suicide, au terrorisme et &agrave; l\'utilisation, la fabrication ou la distribution de substances ill&eacute;gales ou illicites ;</li>
<li>les publications (commentaires, r&eacute;ponses) sans rapport avec les sujets&nbsp;sur lesquelles ils&nbsp;sont publi&eacute;s ("hors sujet") ;</li>
<li>dans le cadre d\'une publication dans un "groupe", les publications (commentaires, sujets, r&eacute;ponses) sans rapport avec la th&eacute;matique propos&eacute;e et sur lesquelles ils sont publi&eacute;s ("hors sujet") ;</li>
<li>la provocation, apologie ou incitation &agrave; commettre des crimes ou des d&eacute;lits et plus particuli&egrave;rement des crimes contre l\'humanit&eacute; ;</li>
<li>les fausses nouvelles.</li>
</ul>
<p>Cette liste n&rsquo;est pas exhaustive.</p>
<p>POLITIZR se r&eacute;serve le droit de ne pas publier certaines contributions, de les publier avec un certain retard ou de les supprimer. POLITIZR n&rsquo;a pas &agrave; motiver cette d&eacute;cision.</p>
<p>De plus, POLITIZR se r&eacute;serve le droit de transmettre les donn&eacute;es personnelles concernant un utilisateur et/ou un abonn&eacute; en vue du respect d&rsquo;une obligation l&eacute;gale, sur demande des autorit&eacute;s de police, administrative ou judiciaires et pour l&rsquo;application d&rsquo;une d&eacute;cision de justice ou &eacute;manant d&rsquo;une autorit&eacute; administrative.</p>
<h1>5 Responsabilit&eacute;</h1>
<p>Les contenus post&eacute;s sur le site rel&egrave;vent de la seule responsabilit&eacute; des contributeurs, POLITIZR ne peut en aucun cas &ecirc;tre tenu pour responsable de ces contenus, notamment de leur caract&egrave;re ill&eacute;gal, d&rsquo;erreurs ou d&rsquo;omissions qu&rsquo;ils pourraient contenir ou de tout dommage cons&eacute;cutif &agrave; leur utilisation.</p>
<p>Conform&eacute;ment &agrave; la loi, d&egrave;s lors qu&rsquo;il sera inform&eacute; de la publication d&rsquo;un contenu susceptible d&rsquo;engager sa responsabilit&eacute; p&eacute;nale, et apr&egrave;s avoir inform&eacute; le contributeur responsable de cette publication, POLITIZR pourra proc&eacute;der &agrave; sa suppression.</p>
', 1, '2017-11-20 10:35:55', '2017-11-20 10:35:55');

# Insertion des topics principaux
INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (1, 1, 'Finances', '
    <h1>Les finances du Conseil départemental en 2018</h1>
', '
<div id="grpBriefCardTitle">Le Débat d\'orientations budgétaires 2018</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Jean-Paul Ferré</b>, Vice-Président du Conseil Départemental délégué à l\'Administration et aux Finances.<br>
        <span>finances</span>
    </div>
    <div class="grpBriefParagraph">
        <h2>Dette du Conseil Départemental de l\'Ariège :<br> 4,2 M€</h2>
        <h2>Dépenses de fonctionnement :<br> 170,1 M€ (+ 2,3 %)</h2>
        • Social : 92,6 M€<br>  
        • Logistique et bâtiment : 2,3 M€<br>
        • Personnel : 48,4 M€<br>
        • Education, culture, sports, patrimoine : 6 M€<br>
        • Economie et tourisme : 5,8 M€<br>
        • Routes : 3,1 M€<br>
        • Aménagement et environnement : 1,6 M€ 
    </div>
    
    <div class="grpBriefImgLeft">
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catFinances-illu1.jpg">
        </div>
        <div class="grpIHaveAQuestion">
            <h2>Question, avis, remarque, ou opinion...</h2>
            <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
            <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
        </div>
    </div>

    <div class="grpBriefParagraph">
        <h2>Dépenses d\'investissement :<br> 56,5 M€</h2>
        dont :<br>
        • Routes : 13,9 M€<br>
        • Bâtiments : 10,4 M€<br>
        • Culture, sports, jeunesse : 2,2 M€<br>
        • Economie et Tourisme : 8,1 M€<br>
        • Agriculture et environnement : 5,2 M€
    </div>
    <div class="grpBriefParagraph">
        <h4>A RETENIR :<br>Le Département est et restera incontournable dans le quotidien des Ariégeoises et des Ariégeois en 2018.</h4>
    </div>
</div>
', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'finances', 1);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (2, 1, 'Très Haut Débit', '
    <h1>Le réseau Très Haut Débit en Ariège</h1>
    ', '
<div id="grpBriefCardTitle">Des ambitions TRÈS HAUT DEBIT</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Christine Téqui</b>, Vice-Présidente du Conseil Départemental déléguée à l\'Economie, au Tourisme, à l\'Agriculture et à l\'Environnement.<br>
        <span>réseau très haut débit</span>
    </div>
    <div class="grpBriefParagraph">
        <b>Une ambition raisonnée et chiffrée :</b><br>
        • 92 000 lignes à connecter<br>
        • 140 millions d\'euros à investir (dont 20 M€ dès 2018)<br>
        • 23 millions d\'euros à la charge du Département de l\'Ariège<br><br>
        
        <b>Une ambition et un calendrier :</b><br>
        • 100 % des foyers fibrés d\'ici à 2025<br>
        • Début des études : en cours<br>
        • Début des travaux : mai 2018
    </div>
    
    <div class="grpBriefParagraph">
        <img src="/bundles/politizrfront/images/grp-CD09/grp-catTHD-illu1.jpg">
        <div class="grpBriefImgLegend">
            Déploiement FTTH et montée en débit de l\'Ariège. Date : Novembre 2017, sources : CD09
        </div>
    </div>
    <div class="grpBriefParagraph">
        <img src="/bundles/politizrfront/images/grp-CD09/grp-catTHD-illu2.jpg">
        <div class="grpBriefImgLegend">
            THD sur le Canton de Val d\'Ariège - Foix. Date : Novembre 2017, sources : CD09
        </div>
    </div>
    
    
    <div class="grpBriefParagraph">
        <div class="grpBriefSidebarLeft">
            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
                <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
            </div>
        </div>  
        <h4>A RETENIR :<br>Un déploiement achevé en 2025 sur l\'ensemble du territoire suite à l\'approbation du contrat de Délégation de Service Public avec la Société Orange concernant la conception, l\'établissement et l\'exploitation du réseau de communication électronique à Très Haut Débit en séance du Conseil départemental le 4 décembre 2017.</h4>
    </div>
</div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'tres-haut-debit', 2);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (3, 1, 'Solidarité', '
    <h1>Le Conseil départemental et la solidarité</h1>
    ', '
<div id="grpBriefCardTitle">La SOLIDARITE adaptée à chacun</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Marie-France Vilaplana</b>, Vice-Présidente du Conseil Départemental déléguée à la Solidarité départementale<br>
        <span>solidarité</span>
    </div>
    <div class="grpBriefParagraph">
        <h2>Plus de 92 M€ mobilisés pour...</h2>
        <b>L\'enfance</b> : suivi des naissances dans le cadre de la Protection Maternelle Infantile, accompagnement des familles et des enfants en difficulté voire placements en famille d\'accueil ou en établissement si l\'enfant ne peut plus rester dans sa famille.<br><br>
        <b>L\'insertion sociale</b> : financement du RSA, des contrats aidés et de nombreux dispositifs d\'insertion.<br><br>
    </div>
    
    <div class="grpBriefImgLeft">
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catSolidarite-illu1.jpg">
        </div>
        <div class="grpIHaveAQuestion">
            <h2>Question, avis, remarque, ou opinion...</h2>
            <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
            <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
        </div>
    </div>

    <div class="grpBriefParagraph">
        <b>L\'accompagnement des handicaps</b> : financement de la PCH (Prestation Compensation Handicap), des places d\'accueil dans les établissements d\'hébergements pour Personnes en Situation de Handicap.<br><br>
        <b>Le soutien aux personnes âgées</b> : versement de l\'APA, soutien financier pour le paiement de l\'hébergement dans les EHPAD.
    </div>
</div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'solidarite', 3);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (4, 1, 'Environnement', '
    <h1>Éclairage public, Gestion de l\'eau, Pastoralisme, ...</h1>
    ', '
<div id="grpBriefCardTitle">Aménager dans le respect de l\'ENVIRONNEMENT</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Christine Téqui</b>, Vice-Présidente du Conseil Départemental déléguée à l\'Economie, au Tourisme, à l\'Agriculture et à l\'Environnement.<br>
        <span>Environnement et aménagement</span>
    </div>
    <div class="grpBriefParagraph">
        <h2>De l\'ingénierie départementale...</h2>
        • Aménagement foncier<br>
        • Autorisation d\'urbanisme<br>
        • Eclairage public<br>
        • Gestion de déchets...<br><br><br>
        
        <h2>Des sujets d\'actualité...</h2>
        • La gestion de l\'eau<br>
        • Les Etats généraux du Pastoralisme dans les Pyrénées…
    </div>
    
    <div class="grpBriefImgLeft">
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catEnvironnement-illu1.jpg">
        </div>
        <div class="grpIHaveAQuestion">
            <h2>Question, avis, remarque, ou opinion...</h2>
            <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
            <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
        </div>
    </div>
</div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'environnement', 4);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (5, 1, 'Éducation', '
    <h1>Collèges Ariégeois et Centre Universitaire à Foix</h1>
    ', '
<div id="grpBriefCardTitle">Mobilisation sur l\'Éducation</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Nicole Quillien</b>, Vice-Présidente du Conseil Départemental déléguée à l\'Education et aux Transports<br>
        <span>éducation</span>
    </div>
    <div class="grpBriefParagraph">
        <h4>En quelques chiffres :<br>
            • 7 000 collégiens<br>
            • 13 collèges et 2 cités scolaires<br>
            • 410 étudiants au Centre universitaire de l\'Ariège à Foix 
        </h4>
    </div>
    
    <div class="grpBriefImgLeft">
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catEducation-illu1.jpg">
        </div>
        <div class="grpIHaveAQuestion">
            <h2>Question, avis, remarque, ou opinion...</h2>
            <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
            <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
        </div>
    </div>

    <div class="grpBriefParagraph">
        <h2>Des priorités en 2018 :</h2>
        • Une restauration scolaire de qualité et de proximité (formation, création de la Légumerie centrale…)<br>
        • Des établissements entretenus, modernisés et équipés<br>
        • Des expérimentations numériques adaptées aux besoins et aux établissements (tablettes par exemple)
    </div>
</div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'education', 5);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (6, 1, 'Culture', '
    <h1>Vie associative, manifestations culturelles et sportives, ...</h1>
    ', '
<div id="grpBriefCardTitle">CULTURE et SPORTS outils de bien-être</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Raymond Berdou</b>, Vice-Président du Conseil Départemental délégué à la Culture, Jeunesse, Sports et Vie associative<br>
        <span>Culture, Jeunesse, Sports</span>
    </div>
    <div class="grpBriefParagraph">
        <h4>En quelques chiffres :<br>
            • 2,8 M€ consacrés à la culture (1,3 % du budget départemental)<br>
            • Plus de 1 000 associations soutenues chaque année<br>
            • 30 manifestations culturelles et sportives labellisées        
        </h4>
    </div>
    
    <div class="grpBriefImgLeft">
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catCulture-illu1.jpg">
        </div>
        <div class="grpIHaveAQuestion">
            <h2>Question, avis, remarque, ou opinion...</h2>
            <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
            <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
        </div>
    </div>
    
    <div class="grpBriefParagraph">
        <h2>Des priorités en 2018 :</h2>
        • Le soutien aux politiques éducatives<br>
        • Le schéma départemental des politiques sportives<br>
        • L\'accompagnement de la professionnalisation des structures (GESCO par exemple)
    </div>
</div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'culture', 6);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (7, 1, 'Routes', '
    <h1>Voirie départementale, centres d\'intervention, ...</h1>
    ', '
<div id="grpBriefCardTitle">Une organisation bien rodée sur les ROUTES</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Alain Naudy</b>, Vice-Président du Conseil Départemental délégué aux Réseaux et Travaux publics. <br>
        <span>Routes départementales</span>
    </div>
    <div class="grpBriefParagraph">
        <h4>En quelques chiffres :<br>
            • 2 666 km de voirie départementale<br>
            • 261 agents répartis sur le département de l\'Ariège<br>
            • 19 centres d\'intervention<br>
            • 13,9 M€ d\'investissements maintenus en 2018                           
        </h4>
    </div>
    
    <div class="grpBriefImgLeft">
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catRoutes-illu1.jpg">
        </div>
        <div class="grpIHaveAQuestion">
            <h2>Question, avis, remarque, ou opinion...</h2>
            <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
            <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
        </div>
    </div>
    
    <div class="grpBriefParagraph">
        <h2>Des priorités en 2018 :</h2>
        • L\'entretien et la modernisation du réseau départemental<br>
        • Les études de la déviation de La Bastide de Bousignac<br>
        • La valorisation des « bois de bord de routes »    
    </div>

    <div class="grpBriefParagraph">
        <h4>
            A RETENIR :<br>
            Au 1er janvier 2018, la compétence « Transport » sera totalement assumée par la Région (loi NOTRe). 
        </h4>
    </div>
</div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'routes', 7);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (8, 1, 'Économie', '
    <h1>Le Conseil départemental soutien l\'économie locale et le tourisme</h1>
    ', '
<div id="grpBriefCardTitle">Améliorer l\'ATTRACTIVITÉ de l\'Ariège</div>
<div class="grpBriefCenter">
    <div id="grpBriefCardInfos">
        Lundi 4 Décembre 2017<br>
        Avec <b>Christine Téqui</b>, Vice-Présidente du Conseil Départemental déléguée à l\'Economie, au Tourisme, à l\'Agriculture et à l\'Environnement.<br>
        <span>Économie et Tourisme</span>
    </div>
    <div class="grpBriefParagraph">
        <h2>En économie, malgré la loi NOTRe, le Département peut encore :</h2>                         
        • Aider pour l\'immobilier d\'entreprise en lien avec les communautés de communes<br>
        • Soutenir l\'investissement des services marchands en zone rurale<br>
        • Financer les structures d\'insertion par l\'activité économique<br>
        • Intervenir sur ses sites touristiques propres (Château de Foix, Parc de la Préhistoire, etc.) ou  en gestion (Grotte du Mas d\'Azil)<br>
        • Accompagner l\'Agence de Développement Touristique Ariège-Pyrénées<br>
        • Initier et soutenir la création de l\'Agence Ariège Attractivité (« Triple A »)
    </div>
    
    <div class="grpBriefImgLeft">
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catEconomie-illu1.jpg">
        </div>
        <div class="grpIHaveAQuestion">
            <h2>Question, avis, remarque, ou opinion...</h2>
            <p>Le Conseil départemental de l\'Ariège vous donne la parole! Que pensez-vous des orientations budgétaires du Département pour 2018?</p>
            <a href="/-w/sujet/nouveau?topic=">Je m\'exprime!</a>
        </div>
    </div>
</div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'economie', 8);

# app/console politizr:uuids:populate PCTopic


# Insertion dans p_c_group_l_c de toutes les villes d'ariège
INSERT INTO `p_c_group_l_c` (`p_circle_id`, `p_l_city_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW() FROM `p_l_city` WHERE `p_l_department_id` = 9;

# Force geoloc topic
UPDATE `p_c_topic` SET `force_geoloc_type`= "department", `force_geoloc_id`=9;

# new > could be done via admin
# Inscription de tous les ariégeois
INSERT INTO `p_u_in_p_c` (`p_circle_id`, `p_user_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW()
FROM `p_user`
WHERE `p_l_city_id` IN (
    SELECT `id` FROM `p_l_city` WHERE `p_l_department_id` = 9
);

# MAJ des droits
UPDATE `p_user` SET `roles` = REPLACE(`roles`, ' ROLE_CIRCLE_1 |', '');

UPDATE `p_user` SET `roles` = CONCAT(`roles`, ' ROLE_CIRCLE_1 |'), `updated_at` = NOW() 
WHERE `p_l_city_id` IN (
    SELECT `id` FROM `p_l_city` WHERE `p_l_department_id` = 9
);
