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
