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
 * Devuelve el mes según el número del mes
 *
 * @param int numMes Número del mes
 * @returns {string}
 */
function getMes(numMes) {
    switch (numMes) {
        case 1:
            return 'Enero';
            break;
        case 2:
            return 'Febrero';
            break;
        case 3:
            return 'Marzo';
            break;
        case 4:
            return 'Abril';
            break;
        case 5:
            return 'Mayo';
            break;
        case 6:
            return 'Junio';
            break;
        case 7:
            return 'Julio';
            break;
        case 8:
            return 'Agosto';
            break;
        case 9:
            return 'Septiembre';
            break;
        case 10:
            return 'Octubre';
            break;
        case 11:
            return 'Noviembre';
            break;
        case 12:
            return 'Diciembre';
            break;
    }
}

/**
 * Obtener la hora formateada
 *
 * @returns {string}
 */
function getHoraFormateada() {
    var fecha = new Date();

    if (fecha.getHours().length == 1) {
        var hora = '0' + fecha.getHours();
    } else {
        var hora = fecha.getHours();
    }

    if (fecha.getMinutes().length == 1) {
        var minutos = '0' + fecha.getMinutes();
    } else {
        var minutos = fecha.getMinutes();
    }

    if (fecha.getSeconds().length == 1) {
        var segundos = '0' + fecha.getSeconds();
    } else {
        var segundos = fecha.getSeconds();
    }

    return hora + ':' + minutos + ':' + segundos;
}

function keyAlphaNum(element, with_space, with_spanhol) {

    if (with_space && with_spanhol) {
        if (element.value.match(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\- ]/g)) {
            element.value = element.value.replace(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\- ]/g, '');
        }
//alert('1.- '+with_space+with_spanhol);
    } else if (with_spanhol) {
        if (element.value.match(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-]/g)) {
            element.value = element.value.replace(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-]/g, '');
        }
//alert('2.- '+with_space+with_spanhol);
    } else if (with_space) {
        if (element.value.match(/[^0-9a-zA-Z\- ]/g)) {
            element.value = element.value.replace(/[^0-9a-zA-Z\- ]/g, '');
        }
//alert('3.- '+with_space+with_spanhol);
    } else {
        if (element.value.match(/[^0-9a-zA-Z\-]/g)) {
            element.value = element.value.replace(/[^0-9a-zA-Z\-]/g, '');
        }
//alert('4.- '+with_space+with_spanhol);
    }

}

/**
 * Esta funcion permite restringir los valores ingresados en un elemento (Texto). Su utilizacion debe ser activada mediante un evento HTML.
 * Ej.: $('#mi_campo').bind('keyup blur', function () {
 * keyText(this, true);
 * });
 *
 * @param JavascriptElement element Puede Ser un Campo de Texto. Ej.: this
 * @param Boolean with_spanhol Denota si debe o no tener SÃ­mbolos o Letras del EspaÃ±ol lo que se ingrese mediante teclado en el campo.
 */
function keyText(element, with_spanhol) {
    if (with_spanhol) {
        if (element.value.match(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-.(),;:_/º ]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-.(),;:_/º ]/g, ''));
        }
    } else {
        if (element.value.match(/[^0-9a-zA-Z\-.(),;:_ ]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-Z\-.(),;:_ ]/g, ''));
        }
    }
}

/**
 * Esta funcion permite restringir los valores ingresados en un elemento (Texto). Su utilizacion debe ser activada mediante un evento HTML.
 * Ej.: $('#mi_campo').bind('keyup blur', function () {
 * keyText(this, true);
 * });
 *
 * PERMITE ADICIONAL A LOS CARACTERES COMUNES LAS LETRAS Ññ, - (GUION), () Y ACENTOS.
 *
 * @param Javascript Element element Puede Ser un Campo de Texto. Ej.: this
 * @param Boolean with_spanhol Denota si debe o no tener SÃ­mbolos o Letras del EspaÃ±ol lo que se ingrese mediante teclado en el campo.
 */
function keyTextDash(element, with_spanhol, with_space) {
    if (with_space && with_spanhol) {
        if (element.value.match(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-() ]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-() ]/g, ''));
        }
    } else {
        if (element.value.match(/[^0-9a-zA-Z\-.()]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-Z-() ]/g, ''));

        }
    }
}

/**
 * Esta funcion permite restringir los valores ingresados en un elemento (Texto). Su utilizacion debe ser activada mediante un evento HTML.
 * Ej.: $('#mi_campo').bind('keyup blur', function () {
 * keyTextOnly(this);
 * });
 * @param JavascriptElement element Puede Ser un Campo de Texto. Ej.: this
 */
function keyTextOnly(element) {
    if (element.value.match(/[^a-zA-Z]/g)) {
        element.value = $.trim(element.value.replace(/[^a-zA-Z]/g, ''));
    }
}
function keyAlpha(element, with_spanhol) {
    if (with_spanhol) {
        if (element.value.match(/[^a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-.(),;: ]/g)) {
            element.value = $.trim(element.value.replace(/[^a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-.(),;: ]/g, ''));
        }
    } else {
        if (element.value.match(/[^a-zA-Z]/g)) {
            element.value = $.trim(element.value.replace(/[^a-zA-Z]/g, ''));
        }
    }
}

function keyLettersAndSpaces(element, with_spanhol) {
    if (with_spanhol) {
        if (element.value.match(/[^a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\ ]/g)) {
            element.value = $.trim(element.value.replace(/[^a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\ ]/g, ''));
        }
    } else {
        if (element.value.match(/[^a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\ ]/g)) {
            element.value = $.trim(element.value.replace(/[^a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\ ]/g, ''));
        }
    }
}


function keyHexa(element, with_dash) {
    if (with_dash) {
        if (element.value.match(/[^0-9a-fA-F\-]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-fA-F\-]/g, ''));
        }
    } else {
        if (element.value.match(/[^0-9a-fA-F]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-fA-F]/g, ''));
        }
    }
}


/**
 * Esta funcion permite restringir los valores ingresados en un elemento (NÃºmeros). Su utilizacion debe ser activada mediante un evento HTML.
 * Ej.: $('#mi_campo').bind('keyup blur', function () {
 * keyNum(this, false);
 * });
 *
 * @param JavascriptElement element Puede Ser un Campo de Texto. Ej.: this
 * @param Boolean with_point Denota si debe o no tener puntos (.) lo que se ingrese mediante teclado en el campo.
 */
function keyNum(element, with_point, negative) {

    if (with_point) {
        if (negative) {
            if (element.value.match(/[^0-9.\-]/g)) {
                element.value = $.trim(element.value.replace(/[^0-9.\-]/g, ''));
            }
        }
        else {
            if (element.value.match(/[^0-9.]/g)) {
                element.value = $.trim(element.value.replace(/[^0-9.]/g, ''));
            }
        }
    } else {
        if (negative) {
            if (element.value.match(/[^0-9\-]/g)) {
                element.value = $.trim(element.value.replace(/[^0-9\-]/g, ''));
            }
        } else {
            if (element.value.match(/[^0-9]/g)) {
                element.value = $.trim(element.value.replace(/[^0-9]/g, ''));
            }
        }
    }
}

/**
 * Esta funcion permite restringir los valores ingresados en un elemento (NÃºmeros). Su utilizacion debe ser activada mediante un evento HTML.
 * Ej.: $('#mi_campo').bind('keyup blur', function () {
 * keyNum(this, false);
 * });
 *
 * @param JavascriptElement element Puede Ser un Campo de Texto. Ej.: this
 * @param Boolean with_point Denota si debe o no tener puntos (.) lo que se ingrese mediante teclado en el campo.
 */
function keyNumCompare(element, with_point) {

    if (with_point) {
        if (element.value.match(/[^0-9.<>=]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9.<>=]/g, ''));
        }
    } else {
        if (element.value.match(/[^0-9<>=]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9<>=]/g, ''));
        }
    }
}


/**
 * Esta funcion permite restringir los valores ingresados en un elemento (Texto). Su utilizacion debe ser activada mediante un evento HTML.
 * Ej.: $('#mi_campo').bind('keyup blur', function () {
 * keyTwitter(this, true);
 * });
 *
 * @param JavascriptElement element Puede Ser un Campo de Texto. Ej.: this
 * @param Boolean with_spanhol Denota si debe o no tener SÃ­mbolos o Letras del EspaÃ±ol lo que se ingrese mediante teclado en el campo.
 */
function keyTwitter(element, with_spanhol) {
    if (with_spanhol) {
        if (element.value.match(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ_@]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ_@]/g, ''));
        }
    } else {
        if (element.value.match(/[^0-9a-zA-Z_@]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-Z_@]/g, ''));
        }
    }
}

/**
 * Esta funcion permite restringir los valores ingresados en un elemento (Texto). Su utilizacion debe ser activada mediante un evento HTML.
 * Ej.: $('#mi_campo').bind('keyup blur', function () {
 * keyEmail(this, true);
 * });
 *
 * @param JavascriptElement element Puede Ser un Campo de Texto. Ej.: this
 * @param Boolean with_spanhol Denota si debe o no tener SÃ­mbolos o Letras del EspaÃ±ol lo que se ingrese mediante teclado en el campo.
 */
function keyEmail(element, with_spanhol) {
    if (with_spanhol) {
        if (element.value.match(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-._@]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-ZáÁéÉíÍóÓúÚñÑäÄëËïÏöÖüÜ\-._@]/g, ''));
        }
    } else {
        if (element.value.match(/[^0-9a-zA-Z\-._@]/g)) {
            element.value = $.trim(element.value.replace(/[^0-9a-zA-Z\-._@]/g, ''));
        }
    }
}

/**
 * Esta función permite limpiar de espacios al inicio o final de los valores ingresados en un campo.
 * Ej.: $('#mi_campo').bind('blur', function () {
 * clearField(this);
 * });
 *
 * @param JavascriptElement element Puede Ser un Campo de Texto. Ej.: this
 */
function clearField(element) {
    element.value = $.trim(element.value);
}

function makeUpper(f) {
    $(f).val($(f).val().toUpperCase());
}

function makeLower(f) {
    $(f).val($(f).val().toLowerCase());
}

function isValidEmail(email) {

    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var valid = emailReg.test(email);

    if (!valid) {
        return false;
    } else {
        return true;
    }

}