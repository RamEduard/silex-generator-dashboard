/**
 * Funciones de javascript de la aplicacion
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

/**
 * Esta funcion efectúa una petición ajax mediante la funcion $.ajax de jquery con manejo de errores.
 * 
 * @param {string} selectorJQuery       Indica el string selector jquery, ya sea por clases o por id, donde se mostrará el resultado.
 * @param {string} uri                  Dirección a donde se efectuará la petición Ajax.
 * @param {string|JSON} data            Datos a enviar junto a la petición ajax, puede estar en formato string serializado o en formato json...
 * @param {boolean} loadingEfect        Indica si muestra el efecto de cargando o no.
 * @param {boolean} showResponse        Indica si muestra el Resultado de la petición Ajax o no.
 * @param {string} method               POST, GET, PUT, DELETE entre otros...
 * @param {string} responseFormat       json, html, xml...
 * @param {function} beforeSendCallback Función que se ejecutará antes de enviar la petición.
 * @param {function} successCallback    Función que se ejecutará luego de enviar la petición, se recibe el dataResponse.
 * @param {function} errorCallback      Función que se ejecutará si se produce un error.
 * 
 * @returns {void}
 */
function ajaxRequest(selectorJQuery, uri, data, loadingEfect, showResponse, method, responseFormat, beforeSendCallback, successCallback, errorCallback) {
    
    if (!method) {
        method = "POST";
    }
    
    if (!responseFormat) {
        responseFormat = "html";
    }
    
    $.ajax({
        type: method,
        url: uri,
        dataType: responseFormat,
        data: data,
        beforeSend: function() {
            if (typeof beforeSendCallback == "function" && beforeSendCallback) {
                beforeSendCallback();
            } else if (loadingEfect) {
                var html = "<div class='padding-5 center'><span class='ion-looping'></span></div>";
                $(selectorJQuery).html('').html(html);
            }
        },
        success: function(response, estatusCode, dom) {
            if (typeof successCallback == "function" && successCallback) {
                successCallback(response, estatusCode, dom);
            } else if (showResponse) {
                $(selectorJQuery).html('').html(response);
            }
        },
        statusCode: {
            404: function() {
                $(selectorJQuery).html('').html("404: No se ha encontrado el recurso solicitado. Recargue la P&aacute;gina e intentelo de nuevo.");
            },
            400: function() {
                $(selectorJQuery).html('').html("400: Error en la petici&oacute;n, comuniquese con el Administrador del Sistema para correcci&oacute;n de este posible error.");
            },
            401: function() {
                $(selectorJQuery).html('').html("401: Datos insuficientes para efectuar esta acci&oacute;n.");
            },
            403: function() {
                $(selectorJQuery).html('').html("403: Usted no est&aacute; autorizado para efectuar esta acci&oacute;n.");
            },
            500: function() {
                $(selectorJQuery).html('').html("500: Se ha producido un error en el sistema, Comuniquese con el Administrador del Sistema para correcci&oacute;n del m&iacute;smo.");
            },
            503: function() {
                $(selectorJQuery).html('').html("503: El servidor web se encuentra fuera de servicio. Comuniquese con el Administrador del Sistema para correcci&oacute;n del error.");
            },
            999: function(resp) {
                $(selectorJQuery).html('').html(resp.status + ': ' + resp.responseText);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            if (xhr.status == '403') {
                var mensaje = "Usted no est&aacute; autorizado para acceder al recurso.";
            } else if (xhr.status == '401') {
                var mensaje = "Usted no est&aacute; autorizado para efectuar esta acci&oacute;n..";
            } else if (xhr.status == '404') {
                var mensaje = "Recurso no encontrado. Recargue la P&aacute;gina e intentelo de nuevo.";
            } else if (xhr.status == '500') {
                var mensaje = "Ha ocurrido un error de servidor. Comun&iacute;quese con el Administrador del Sistema para correcci&oacute;n del m&iacute;smo.";
            } else if (xhr.status == '503') {
                var mensaje = "El servidor web se encuentra fuera de servicio. Comuniquese con el Administrador del Sistema para correcci&oacute;n del error.";
            }
            if (typeof errorCallback == "function" && errorCallback) {
                errorCallback(xhr, ajaxOptions, thrownError, mensaje);
            } else {
                $(selectorJQuery).html('').html(mensaje);
            }
        }
    });
}

/**
 * Crear
 * 
 * @param {String} selector Indica el string selector jquery, ya sea por clases o por id, donde se mostrará el modal
 * @param {String} title    Indica el titulo del modal
 * @param {String} body     Indica el contenido del modal
 * @param {String} footer   Indica el footer donde normalmente estan los botones de accion
 *
 * @returns {Object}
 */
function verModalDialog(selector, title, body, footer, style) {
    var templateModal = '<div class="modal-dialog">'+
                            '<div class="modal-content">'+
                              '<div class="box modal-header">'+
                                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>'+
                                '<h4 class="modal-title">Titulo</h4>'+
                              '</div>'+
                              '<div class="modal-body">'+
                                '<p>Contenido</p>'+
                              '</div>'+
                              '<div class="modal-footer">'+
                                '<div class="modalResponse" style="display:inline-block; float:left"></div>'+
                                '<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>'+
                                '<button type="submit" class="btn btn-primary">Enviar</button>'+
                              '</div>'+
                            '</div><!-- /.modal-content -->'+
                          '</div><!-- /.modal-dialog -->';
          
    var modal = $(templateModal),
        modalHeader = $('.modal-header', modal),
        modalTitle = $('.modal-title', modalHeader),
        modalBody = $('.modal-body', modal),
        modalFooter = $('.modal-footer', modal);

    if (!selector) {
        console.log('No hay selector');
    } else {
        if (title) {
            modalTitle.html(title);
        }
        if (body) {
            modalBody.html(body);
        }
        if (footer) {
            modalFooter.html(footer);
        }
        if (!style) {
            modalHeader.addClass('modal-info');
        } else {
            modalHeader.addClass('modal-' + style);
        }
        return $(selector).addClass('modal fade').html(modal).modal('show');
    }
}