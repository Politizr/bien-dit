# Insertion du propriétaire du groupe
INSERT INTO `p_c_owner` (`id`, `uuid`, `title`, `summary`, `description`, `created_at`, `updated_at`, `slug`)
VALUES ('1', NULL, 'Conseil Départemental de l\'Ariège', '<p>Conseil Départemental de l\'Ariège</p>', '<p>Conseil Départemental de l\'Ariège</p>', now(), now(), 'conseil-departemental-de-l-ariege');

# app/console politizr:uuids:populate PCOwner

# Insertion du groupe
INSERT INTO `p_circle` (`id`, `p_c_owner_id`, `uuid`, `title`, `summary`, `description`, `url`, `online`, `only_elected`, `created_at`, `updated_at`, `slug`, `sortable_rank`)
VALUES ('1', 1, NULL, 'Budget Primitif 2017', '<p>Budget Primitif 2017</p>', '
                    <div class="grpGlobalIntro">
                        <div class="grpGlobalIntroBigArticle">
                            <img src="/bundles/politizrfront/images/grp-CD09/grp-bigArticle.png">
                            <div class="grpGlobalIntroOneCol">
                                <h1>Présentation du contexte porta sem suada magna mollis</h1>
                                <p><b>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.</b><br>
Curabitur blandit tempus porttitor. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum.<br>
                                    <a class="grpKnowMore" target="_blank" href="">En savoir plus sur le site du Département</a>
                                </p>
                            </div>
                        </div>
                        <div class="grpGlobalIntroSmallArticles">
                            <div class="grpGlobalIntroOneCol">
                                <img src="/bundles/politizrfront/images/grp-CD09/grp_article1.jpg">
                                <h1>Praesent Fusce Venenatis commodo cursus magna velisque</h1>
                                <p>Etiam porta sem malesuada magna mollis euismod. Vivamus sagittis lacus vel augue laoreet rutrum.</p>
                            </div>
                            <div class="grpGlobalIntroOneCol">
                                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Donec sed odio dui. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec sed odio dui. Vestibulum id ligula porta felis euismod semper. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.</p>
                            </div>
                            <div class="grpGlobalIntroTwoCol">
                                <img src="/bundles/politizrfront/images/grp-CD09/grp_article2.jpg">
                                <h1>Vestibulum id ligula porta felis euis mod semper.</h1>
                                <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Etiam porta sem malesuada magna mollis euismod.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec sed odio dui.</p>
                            </div>
                        </div>
                        <div class="grpTwitterFeed">
                            <div class="grpTwitterLogo">
                                <i class="icon-social-tw"></i><br>
                                Mentions<br>
                                <span>#09Budg2017</span>
                            </div>
                            <a class="twitter-timeline" href="https://twitter.com/hashtag/Ari%C3%A8ge" data-widget-id="913411922724352000" data-chrome="nofooter noheader noborders noscrollbar transparent" data-width="180" data-height="625">></a> 
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                        </div>
                    </div>
', NULL, '1', '0', now(), now(), 'conseil-departemental-ariege', 1);

# app/console politizr:uuids:populate PCircle

# Insertion de la charte du groupe
INSERT INTO `p_m_charte` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (2, 1, 'Charte Ariège le département Budget Primitif', 'v1.0','
<h1>1 Pr&eacute;ambule</h1>
<p>En compl&eacute;ment de sa plate-forme g&eacute;n&eacute;rale, POLITIZR a con&ccedil;u et d&eacute;velopp&eacute; des espaces de discussion permettant aux collectivit&eacute;s de discuter avec leurs administr&eacute;s.</p>
<p>Cette charte s\'applique pour la collectivit&eacute; "Ari&egrave;ge le d&eacute;partement" au sujet du groupe "Budget Primitif 2017": elle a &eacute;t&eacute; r&eacute;dig&eacute;e conjointement par POLITIZR et le Conseil D&eacute;partemental de l\'Ari&egrave;ge.</p>
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
VALUES (1, 1, 'Agriculture', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'agriculture', 1);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (2, 1, 'Culture', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
    ', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'culture', 2);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (3, 1, 'Économie', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
    ', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'economie', 3);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (4, 1, 'Éducation', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
    ', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'education', 4);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (5, 1, 'Jeunesse', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
    ', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'jeunesse', 5);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (6, 1, 'Solidarité', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
    ', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'solidarite', 6);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (7, 1, 'Sport', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
    ', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'sport', 7);

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank`) 
VALUES (8, 1, 'Tranports', '
    <h1>Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec ullamcorper nulla non metus auctor fringilla.</h1>
    ', '
    <div id="grpBriefCardTitle">Cras justo odio, dapibus ac facilisis in, egestas eget quam. Sed posuere consectetur est at lobortis.</div>
    <div class="grpBriefCenter">
        <div id="grpBriefCardInfos">
            Lundi 17 Novembre 2017<br>
            par le service communication du conseil Départemental de l’Ariège<br>
            <span>transports</span>
        </div>
        <div class="grpBriefParagraph">
            <h2>Nulla vitae elit libero, a pharetra augue. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</h2>
            Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed odio dui.<br>
Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. 
        </div>
        
        <div class="grpBriefImgLeft">
            <div class="grpImg">
                <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu1.jpg">
            </div>
            <div class="grpBriefImgLegend">
                Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
            </div>

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

        </div>

        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. 
        </div>
        
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarRight grpBriefBigNumber">
                <h1>1<span>€</span></h1><br>
                Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nullam quis risus eget urna mollis ornare vel eu leo. Cras justo odio, dapibus ac facilisis in, egestas eget quam.
            </div>
            Vestibulum id ligula porta felis euismod semper. Nullam quis risus eget urna mollis ornare vel eu leo. Maecenas sed diam eget risus varius blandit sit amet non magna. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec id elit non mi porta gravida at eget metus.
        </div>                      
        <div class="grpBriefParagraph">
            <h4>Donec id elit non mi porta gravida at eget metus. Vestibulum id ligula porta felis euismod semper. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Aenean lacinia bibendum nulla sed consectetur. Nullam quis risus eget urna mollis ornare vel eu leo.</h4>
        </div>                      
        <div class="grpBriefParagraph">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Donec id elit non mi porta gravida at eget metus. Maecenas faucibus mollis interdum. Nullam id dolor id nibh ultricies vehicula ut id elit. Maecenas sed diam eget risus varius blandit sit amet non magna. 
        </div>
        <div class="grpBriefParagraph">
            <h3>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</h3>
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">
                <div class="grpImg">
                    <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu2.jpg">
                </div>
                <div class="grpBriefImgLegend">
                    Vestibulum id ligula porta felis euismod semper.
                </div>
            </div>                          
            Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Praesent commodo cursus magna, vel scelerisque nisl consectetur et.                  
        </div>
    </div>
    
    <div class="grpBriefImgH1">
        <h1>Nullam id dolor id nibh ultricies vehicula ut id elit. Penatibus et magnis dis, nascetur ridiculus mus.</h1>
        <div class="grpBriefImgH1Shadow"></div>
        <div class="grpImg">
            <img src="/bundles/politizrfront/images/grp-CD09/grp-catTransport-illu3.jpg">
        </div>
        <div class="grpBriefImgLegend">
            Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor.
        </div>
    </div>
    
    <div class="grpBriefCenter">
        <div class="grpBriefParagraph">
            Donec id elit non mi porta gravida at eget metus. Aenean lacinia bibendum nulla sed consectetur. Curabitur blandit tempus porttitor. Vestibulum id ligula porta felis euismod semper. <b>Etiam porta sem malesuada magna mollis euismod.</b> Cras mattis consectetur purus sit amet fermentum. Donec id elit non mi porta gravida at eget metus.
Etiam porta sem malesuada magna mollis euismod. Cras mattis consectetur purus sit amet fermentum. 

            <div class="grpBriefSidebarRight grpBriefGraph">
                <p>Maecenas sed diam eget risus varius blandit sit amet non magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                <img src="/bundles/politizrfront/images/grp-CD09/grp_graph.png">
                <p>Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Sed posuere consectetur est at lobortis.</p>
            </div>
        </div>
        <div class="grpBriefParagraph">
            <h3>Maecenas sed diam eget risus varius blandit sit amet non magna.</h3>
        </div>
        <div class="grpBriefParagraph">
            Nullam quis risus eget urna mollis ornare vel eu leo. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus mollis interdum. 
        </div>
        <div class="grpBriefParagraph">
            Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Donec ullamcorper nulla non metus auctor fringilla. Aenean lacinia bibendum nulla sed consectetur. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Sed posuere consectetur est at lobortis.<br>
Maecenas faucibus mollis interdum. Donec sed odio dui. Donec id elit non mi porta gravida at eget metus. Donec id elit non mi porta gravida at eget metus. <a href="">Curabitur blandit tempus porttitor.</a> Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras justo odio, dapibus ac facilisis in, egestas eget quam. 
        </div>
        <div class="grpBriefParagraph">
            <div class="grpBriefSidebarLeft">

            <div class="grpIHaveAQuestion">
                <h2>Question, avis, remarque, ou opinion...</h2>
                <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.  Integer posuere erat a ante.</p>
                <a href="">J\'ai une question!</a>
            </div>

            </div>                  
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum. Maecenas sed diam eget risus varius blandit sit amet non magna. Maecenas sed diam eget risus varius blandit sit amet.           
        </div>
    </div>
    ', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'transports', 8);

# app/console politizr:uuids:populate PCTopic


# Insertion dans p_c_group_l_c de toutes les villes d'ariège
INSERT INTO `p_c_group_l_c` (`p_circle_id`, `p_l_city_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW() FROM `p_l_city` WHERE `p_l_department_id` = 9;

# Force geoloc topic
UPDATE `p_c_topic` SET `force_geoloc_type`= "department", `force_geoloc_id`=9;

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

# MAJ des users ayant l'autorisation de répondre
UPDATE `p_u_in_p_c` SET `is_authorized_reaction` = 1 WHERE `p_user_id` = 3;
