    /**
     * 
     */
    
    /**
     * Todos os formulários deste sistema seguem este padrão
     *   -- Se houver formulário carregado então inicializa...
     */
    if ($("form").length >= 1) {
        _initMask();
    }


    /**
     * Função que irá iniciar as mascaras dos inputs do formulário
     * 
     * @param {type} [tipo]
     * @returns {void}
     */
    function _initMask() 
    {
    }



    /**
     * Função que controla a paginação via ajax
     */
    $(document).on("click", ".pagination li a", function() {
        var url         = $(this).attr('href'),
            divTarget   = $("#areaResultado #divResultAno").length ? "#areaResultado #divResultAno" : "#areaResultado";

        if (url != 'javascript:void(0)') {
            $(divTarget).html('<div class="text-center"><br /><br /><img src="/js/modules/default/layouts/imagens/icones/carregando.gif" alt="carregando" style="width: 10%" ><br /><p>Carregando...</p></div>');
            $.get(url, {}, function(r) {
                try {
                    $(divTarget).html(r).promise().done(function() {
                        $(".pagination li a").each(function(i, e) {
                            $(e).attr('href', $(e).attr('href').replace(/(<([^>]+)>)/ig, ''));
                        });
                    });                
                } catch (e) {
                    $(divTarget).show().html('<h3>Erro ao localizar as informações no diário oficial</h3>N�o foi possível localizar as informações no DODF. Tente novamente mais tarde!');
                }
            });
        }
        return false;
    });


    /**
     * Função que controla a listagem de anos via ajax
     */
    $(document).on("click", ".listarAnos li a", function() {
        var url = $(this).attr('href');

        //-- Inserindo
        $(".listarAnos li.active").removeClass('active');
        $(this).parent().addClass("active");

        if (url != 'javascript:void(0)') {
            $("#areaResultado #divResultAno").html('<div class="text-center"><br /><br /><img src="/js/modules/default/layouts/imagens/icones/carregando.gif" alt="carregando" style="width: 10%" ><br /><p>Carregando...</p></div>');
            $.get(url, {}, function(r) {
                try {
                    $("#areaResultado #divResultAno").html(r).promise().done(function() {
                        $(".pagination li a").each(function(i, e) {
                            $(e).attr('href', $(e).attr('href').replace(/(<([^>]+)>)/ig, ''));
                        });
                    });                
                } catch (e) {
                    $("#areaResultado #divResultAno").show().html('<h3>Erro ao localizar as informações no diário oficial</h3>N�o foi possível localizar as informações no DODF. Tente novamente mais tarde!');
                }
            });
        }

        return false;
    });




    /** Função de fazer com que o sistema informe que esta havendo uma requisição ajax */
//    $(document).ajaxSend(function (e, jqxhr, settings) {
//        $("body").prepend('<div class="ajaxCarregando"></div>');
//        $(".ajaxCarregando").html('Carregando, aguarde...').show();
//
//    }).ajaxStop(function(){
//        $(".ajaxCarregando").remove();
//    });
    
    
    // Sempre que houver click sobre um btnEnviar o sistema irá tratar o envio no método ajax...
    $(document).on("click", "#submit", function() {
        var formId  = $(this).parents('form').attr('id'),
            tipo    = $("#"+formId).attr('action').split('/')[2],
            self    = this;
        
        $(self).button('loading');
        $result = ajaxSubmit(formId);
        
        $.when($result).then(function(r) {
            if (r['type'] === 'success' && tipo !== 'editar') {
                $("#"+formId +' :input').not(':button, :submit, :reset, :hidden').val('')
                                        .removeAttr('checked')
                                        .removeAttr('selected');
            }
            $("html, body").animate({scrollTop:200},"slow");
            $(self).button('reset');
        });
        
        // Evitar que o botão submeta o formulário...
        return false;
    });    


    /**
     * Faz com que os formulários sejam mais organizados, podendo ter formulário com campos lado a lado
     * -- Para evitar consumo de recurso ele sempre verifica se existe formulário...
     *
     * @param {string} [tipo] #context|#systemModal
     */
    function initFormulario(tipo)
    {
        var initNav = false,
            loopNav = 0;
            ie68    = ($("ie6, .ie7, .ie8").length >= 1 ? true : false)

        $.each($(tipo+" fieldset"), function(ev, el) {
            var idElement   = $(this).attr("id"),
                type        = idElement.split("_")[0].split("-")[1].replace(/[0-9]/g, ''),      //-- String - Nav ou Group
                id          = idElement.split("_").splice(1, idElement.split("_").length),      //-- Array
                idNav       = idElement.split("_")[0].split("-")[1].replace(/[A-Za-z$-]/g, ''), //-- String - ID Atual
                nameNav     = $(this).attr("data-label"),                                       //-- String - Nome no navigator
                contentNav  = '';                                                               //-- String - Pega o conteúdo modificado para inserir no navigator

            for (var i in id) {
                if (type === 'group') {
                    $(tipo+" #"+idElement).find("div.form-group").eq(i).wrap('<div class="col-sm-'+ id[i] +'" />');

                } else if (type === 'nav') {
                    // Inicializa o sistema de navs do bootstrap
                    if (initNav === false && !ie68) {
                        $(tipo+" div.form-group:last").prepend('<ul class="nav nav-tabs" style="margin-top: 10px;" />');
                        $(tipo+" .nav.nav-tabs").after('<div class="tab-content" />');
                        initNav = true;
                    }

                    // Insere uma nav-tabs e uma div correspondente para o mesmo...
                    if ($(tipo+" #tab"+idNav).length === 0) {
                        $(tipo+" .nav.nav-tabs").append('<li class="'+ ((idNav === '1') ? 'active' : '') +'"><a href="#tab'+ (idNav) +'" data-toggle="tab">'+ nameNav +'</a></li>')
                        $(tipo+" .tab-content").append('<div class="tab-pane '+ (idNav === '1' ? 'active' : '') +'" id="tab'+ (idNav) +'" />');
                    }

                    // Salva o conteúdo criado em uma variável....
                    contentNav += $(tipo+" #"+idElement).find("div.form-group").eq(i).wrap('<div class="col-sm-'+ id[i].replace(/[A-Za-z$-]/g, '') +'" />').parent().wrap('<div />').parent().html();

                    // Insere o conteúdo no menu-inferior
                    if (id[i].substr(-1) === 'f') {
                        $(tipo+" #tab"+idNav).append('<div class="row">'+ contentNav +'</div>');
                        contentNav = '';
                    }

                    // Verifica se o conteúdo foi completamente replicado, e reinicializa as contagens
                    if (++loopNav === id.length) {
                        loopNav     = 0;
                        contentNav  = '';

                        // Remove o conteúdo anterior somente se não for IE8
                        if (!ie68) {
                            $(this).remove();
                        }
                    }
                }
            }
        });
    }


    //INICIANDO FORM: Funções genéricas para os formulários deste módulo.
    if ($("form").length >= 1) {
        initFormulario('#context');
    }


    //ENVIO: Sempre que houver click sobre um btnEnviar o sistema irá tratar o envio no método ajax...
    $(document).on("click", "#btnEnviar", function() {
        var formId  = $(this).parents('form').attr('id'),
            tipo    = $("#"+formId).attr('action').split('/')[3],
            self    = this;

        $(self).button('loading');
        $result = ajaxSubmit(formId);

        $.when($result).then(function(r) {
            if (r['type'] === 'success' && tipo !== 'editar') {
                $("#"+formId +' :input').not(':button, :submit, :reset, :hidden').val('')
                                        .removeAttr('checked')
                                        .removeAttr('selected');
            }
            $("html, body").animate({scrollTop:200},"slow");
            $(self).button('reset');
        });

        // Evitar que o botão submeta o formulário...
        return false;
    });


    /**
     * função chave para o processo de envio de dados do formulário via ajax e resposta json.
     *
     * @param {string} [formName] Informar apenas o id do formulário, NÃO o objeto formulário
     * @param {string} [succ]
     * @return {json} [data]
     */
    function ajaxSubmit(formName)
    {
        var self    = this,
            url     = $("#"+formName).attr('action'),
            dados   = $("#"+formName).serialize();

        // Remove os antigos erros do formulário e cria padrão de mensagem do servidor
        removerErrosFormulario();
        inserirMensagemSistema(formName);

        // Verifica se a forma de enviar o conteúdo é via post ou via formAjaxSubmit
        if (typeof $("form").find('input:file').get(0) === "undefined") {
            return submitInfoFormAjax(url, dados, formName);

        // Método especial para formulários com arquivos
        } else {
            submitFormFileAjax(formName)
        }
    };


    /**
     * Sempre que um formulário for submetido é interessante remover os erros para que seja feita uma nova análise
     * -- Remove os erros da página inteira, se houver outra necessidade esta função deverá ser atualizada...
     *
     * @returns {void}
     */
    function removerErrosFormulario()
    {
        $("ul[id^='errors-']").remove();
        $(".form-group.has-error").removeClass('has-error');
    }


    /**
     * Coloa a mensagem do sistema em cima do formulário. Informando se tudo ocorreu corretamente ou se houve algum problema
     * -- Esta mensagem será preenchida com informações do servidor, nunca na tela do usuário...
     *
     * @param {string} formName
     * @returns {void}
     */
    function inserirMensagemSistema(formName)
    {
        // Verifica se a mensagem do sistema já foi criada na tela do usuário...
        if (typeof $(".msgSystemAlert").get(0) === 'undefined') {
            $('<div class="msgSystemAlert collapse"><div class="alert"><button type="button" data-toggle="collapse" data-target=".msgSystemAlert" class="close">&times;</button><p></p></div></div>').prependTo("#"+formName);
        }
    }


    /**
     * Função chamada pelo ajaxsubmit ou por outra função cujo o interesse seja enviar informações via POST
     * e retornar erros do formulário através do servidor.
     * -- Esta função irá
     *
     * @param {string} [url]
     * @param {object} [dados]
     * @param {object} [formName] Obrigatório, pois haverá validação do formulário no servidor
     *                            Se nao tiver formulário, utilize o $.post...
     * @returns {mixed}
     */
    function submitInfoFormAjax(url, dados, formName)
    {
        // Envia os dados do formulário para o servidor.
        return $.post(url, dados, function(resp) {
            defaultValidations(formName, resp);
        }, "json");
    }


    /**
     * Função que submete informações de um formulário com arquivo anex via "ajax" (não é ajax de verdade)
     *
     * @param {string} formName
     * @returns {mixed}
     */
    function submitFormFileAjax(formName)
    {
        $("#"+formName).iframePostForm({
            post : function() {
                $("body").prepend('<div class="ajaxCarregando"></div>');
                $(".ajaxCarregando").html('Carregando, aguarde...').show();
            },
            complete : function (response) {
                resp = response.replace('<pre style="word-wrap: break-word; white-space: pre-wrap;">', '').replace('<pre>', '').replace('</pre>', '');
                resp = $.parseJSON(resp);

                defaultValidations(formName, resp);

                $("#iframe-post-form").remove();
                $(".ajaxCarregando").remove();
            },
            iframeID: 'iframe_'+ Math.floor((Math.random()*100)+1) +'_'+ Math.floor((Math.random()*100)+1)
        }).submit();
    }


    /**
     * Este sistema possui um padrão próprio de validação de formulários via ajax.
     * -- Este padrão poderá ser utilizado em qualquer submissão ajax que receba retorno
     * -- Útil em formulários de envio de arquivos com necessidade de retorno de informações
     *
     * @param {string} [formName] Id do form
     * @param {string} [resp] Geralmente é o resultado do servidor com base no formulário enviado
     * @returns {void}
     */
    function defaultValidations(formName, resp)
    {
            // Prepara as informaçoes e o formulário...
            inserirMensagemSistema(formName);
            removerErrosFormulario();

            // Verificar se houve erro na inclusão das informações.
            if (resp.type === 'success') {
                // Mensagem de sucesso!
                $(".msgSystemAlert").addClass('in').removeAttr('style').children().removeClass('bg-danger').addClass('bg-success').find('p').html(resp.flashMsg);

                if (typeof resp.redirect !== 'undefined' && resp.redirect != '' && resp.redirect != null)
                    window.location = resp.redirect;

            } else {
                // Insere os erros do formulário, caso exista...
                insertInputError(formName, resp);

                // Mensagem de Erro!
                $(".msgSystemAlert").addClass('in').removeAttr('style').children().removeClass('bg-success').addClass('bg-danger').find('p').html(resp.flashMsg);
            }
    }


    /**
     * Injeta a mensagem de erro no formulário especificado nos campos encontrados.
     *
     * @param {string} [formName] Id do form
     * @param {string} [resp] Geralmente é o resultado do servidor com base no formulário enviado
     * @returns {void}
     */
    function insertInputError(formName, resp)
    {
        // Busca os componentes do formulário que foram validados no servidor.
        $("#"+formName).find("input, textarea, select, radio, checkbox").each(function() {
            inputId = $(this).attr('id');

            // Remove os erros já reportados, para que sejam validados novamente...
            $("#"+formName+" #" + inputId).parent().find('.errors').remove();

            // Verifica se houve erros e para cada compontente invalidado coloca o erro
            if (typeof resp.erros !== 'undefined') {
                $("#"+formName+" #" + inputId).parents('.form-group').append( self.getErrorHtml(resp.erros[$(this).attr('name')], inputId) );
                $("ul.errors li").closest('.form-group').addClass('has-error');
            }
        });
    }


    /**
     * Função que reproduz os erros no formulário.
     * Gerando os li que ficarão em baixo do input com problema.
     *
     * @param {string} [formErrors]
     * @param {string} [input]
     * @return {html.tag} [erros]
     */
    function getErrorHtml( formErrors, input )
    {
        var erros = '<ul id="errors-'+input+'" class="errors">';
        for (var i in formErrors) {
            erros += '<li>' + formErrors[i] + '</li>';
        }
        erros += '</ul>';

        return erros;
    };


    /**
     * Função que valida o imput específico do formulário. TODOS os erros do formulário serão
     * passados via json, porém, a função fica encarregada de gerar apenas os erros do input.focusout
     * Muito útil para utilizar junto com focusout
     *
     * @param {string} [formName]
     * @param {string} [link]
     * @param {string} [input]
     */
    function validarInputs(formName, input, link)
    {
        var url     = link;
        var dados   = $("#"+formName).serialize();

        $.post(url, dados, function(data)
        {
            $("#"+input).parent().find('.errors').remove();
            if (typeof data.erros !== 'undefined') {
                $("#"+input).parent().append(this.getErrorHtml(data.erros[input], input ));
            }
        }, "json");
    }