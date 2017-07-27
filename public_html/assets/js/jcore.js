/**
 *
 *
 * @copyright   © 2017 Brayan Rincon
 * @version     2.0.0
 * @author      Brayan Rincon <brayan262@gmail.com>
 */
var jCore = (function() {

    var property = {

        },

        Methods = {

        },

        base = {
            registry: function(className, directory) {
                try {
                    switch (className) {
                        default: include_once(directory, 'script');
                        break;
                    }
                    var _class = "" + className + "";
                    jCore.extend(this.base, { _class: className });
                } catch (ex) {
                    alert('Error al registrar Clase: "' + className + '"\n' + ex.message);
                }
            }
        };

    return {
        version: '2.0',

        author: 'Brayan Rincon <brincon@megacreativo.com>',

        initialize: function() {
            return true;
        },

        extend: function(destination, source) {
            for (var property in source) {
                destination[property] = source[property];
            }
            return destination;
        }
    };

})();


/**
 *  Especifica o asigna una variable de tipo Object
 *  @Const
 *  @type {string}
 **/
var _abstract_ = {};


/**
 *  Especifica o asigna que un variable de tipo referencia no está apuntando a nada o no está instanciado a objeto alguno.
 *  @Const
 *  @type {string}
 **/
var _nothing_ = undefined;



/** Contiene los tipos de Objetos. */
var _type_ = {
    /** Especifica o asigna que un variable de tipo referencia no está apuntando a nada,
     *  o no está instanciado a objeto alguno. @type {undefined} */
    'nothing': _nothing_,

    /** Indica que el tipo de un objeto Null. @type {string} null */
    'null': 'null',

    /** Indica que el tipo de un objeto es Boolean. @type {string} boolean */
    'bool': 'boolean',

    /** Indica que el tipo de un objeto es Boolean. @type {string} boolean */
    'boolean': 'boolean',

    /** Indica que el tipo de un objeto es Number. @type {string} number */
    'number': 'number',

    /** Indica que el tipo de un objeto es String. @type {string} string */
    'string': 'string',

    /** Indica que el tipo de un objeto es Object. @type {string} object */
    'object': 'object',

    /** Indica que el tipo de un objeto es [object Boolean]. @type {string} [object Boolean] */
    'boolean_class': '[object Boolean]',

    /** Indica que el tipo de un objeto es [object Number]. @type {string} [object Number] */
    'number_class': '[object Number]',

    /** Indica que el tipo de un objeto es [object String]. @type {string} [object String] */
    'string_class': '[object String]',

    /** Indica que el tipo de un objeto es [object Object]. @type {string} [object Object] */
    'object_class': '[object Object]',

    /** Indica que el tipo de un objeto es Function. @type {string} Function */
    'function': Function
};




//Windows
(function(global) {

    var object_proto = Object.prototype,
        array_proto = Array.prototype,
        number_proto = Number.prototype,
        string_proto = String.prototype,
        procedure = new Function("return false;");

    jCore.extend(global, {
        'object_proto': object_proto,
        'array_proto': array_proto,
        'number_proto': number_proto,
        'string_proto': string_proto
    });

    /**
     *  Devuelve un valor Boolean que indica si un objeto es una instancia de una clase concreta.
     *  @param {object} Pattern Obligatorio. Cualquier expresión de objeto. 
     *  @param {object} Type Cualquier clase de objeto definida.
     *  @return {boolean}
     **/
    global.is_instance = function(Pattern, Type) {
        return (Pattern instanceof Type);
    };

    /**
     *  Devuelve un valor Boolean que indica el Objeto Pattern e del tipo del dato especificado y Type.
     *  @param {object} Pattern Obligatorio. Cualquier expresión de objeto. 
     *  @param {object} Type Cualquier clase de objeto definida.
     *  @return {boolean}
     **/
    function type_of(Pattern, Type) {
        return (typeof Pattern == Type);
    };

    /**
     *  Devuelve una cadena que identifica el tipo de datos de una expresión.
     *  @param {object} Pattern Obligatorio. Cualquier expresión de objeto. 
     *  @return {string}
     **/
    function get_type_(Pattern) {
        switch (Pattern) {
            case null:
                return _type_.null;
            case _nothing_:
                return _nothing_;
        }
        var t = typeof Pattern;
        switch (t) {
            case 'boolean':
                return _type_.boolean;
            case 'number':
                return _type_.Number;
            case 'string':
                return _type_.string;
        }
        return _type_.Object;
    };


    global.user_agt = navigator.userAgent;


    global.time_stamp = function() {
        var d = new Date();
        return d.getFullYear() + '/' + d.getMonth() + '/' + d.getDate() + ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
    };

    /**
     * Browser
     * @return {Object} Navegador
     */
    global.browser = (function() {
        return {
            ie: (user_agt.indexOf('IE') != -1),
            opera: (user_agt.indexOf('Opera') != -1),
            gecko: (user_agt.indexOf('Gecko') > -1 && user_agt.indexOf('KHTML') === -1),
            netscape: (user_agt.indexOf('Netscape') != -1),
            safari: (user_agt.indexOf('AppleWebKit') != -1),
            mobile_safari: (!!user_agt.match(/Apple.*Mobile.*Safari/)),
            name: (user_agt.appCodeName),
            cookie_enabled: (user_agt.cookieEnabled),
            windows: (navigator.platform == 'Win32' || navigator.platform == 'Win64'),
            XP: (user_agt.indexOf('NT 5.1') != -1),
            vista: (user_agt.indexOf('NT 6') != -1),
            macintosh: (user_agt.indexOf('Mac') != -1),
            windows7: (user_agt.indexOf('NT 6.1') != -1),
            linux: (user_agt.indexOf('Linux') != -1),
            iPhone: (user_agt.indexOf('iPhone') != -1),
            iPod: (user_agt.indexOf('iPod') != -1),
            iPad: (user_agt.indexOf('iPad') != -1),
            android: (user_agt.indexOf('Android') != -1)
        };
    })();

    global.is_ie = browser.ie;
    global.is_gecko = browser.gecko;
    global.is_opera = browser.opera;

    global.isset = function(Reference) {
        return !(typeof Reference === "undefined");
    };


    global.str_random = function(len) {
        len = len ? len : 5;
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < len; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        return text;
    }

    global.guid = function() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    };

    /**
     * Crear un Pop Up
     */
    global.popup = function(URL, Arguments, WindowsName) {
        if (!isset(URL) || !isset(Arguments)) {
            return false;
        }
        Properties = '';
        WindowsName = (WindowsName) ? WindowsName : '';

        for (var Property in Arguments) {
            Properties += Property + '=' + Arguments[Property] + ',';
        }

        Windows.open(URL, WindowsName, Properties);
    };


    global.now = function() {
        return new Date().getTime();
    };

    global.is_string = function(Sender) {
        return (!isset(Sender) ? false : Object.IsString(Sender));
    };


    /* Devuelve True si el argumento pasado es un Array, False en caso contrario */
    global.is_array = function(Sender) {
        return (!isset(Sender)) ? false : Object.is_array(Sender);
    };

})(this);

//Object
(function() {

    var _toString = function(Reference) { return object_proto.toString.call(Reference); };

    function _extend(Destination, Source) {
        for (var Property in Source) {
            Destination[Property] = Source[Property];
        }
        return Destination;
    }

    function is_string(Reference) {
        return _toString(Reference) == _type_.string_class;
    }

    function is_number(Reference) {
        return _toString(Reference) == _type_.number_class;
    }

    function is_array(Reference) {
        return Reference != null && typeof Reference == _type_.object && 'splice' in Reference && 'join' in Reference;
    }

    function is_function(Reference) {
        return IsInstance(Reference, Function);
    }

    function is_null(Reference) {
        return (Reference == null) ? true : false;
    }

    function is_control(Reference) {
        return !!(Reference && Reference.nodeType == 1);
    }

    function set_type_(Type) {
        this.setAttribute('jcType', Type);
        return this;
    }

    function get_type_() {
        return this.get_attr('jcType');
    }

    function name(NameElement) {
        if (NameElement) {
            this.setAttribute('name', NameElement);
            return this;
        } else {
            return this.getAttribute('name');
        }
    }


    function add_method(Name, Source) {
        var NameFunction = this[Name];
        this[Name] = function() {
            if (Object.is_function(Source)) {
                if (Source.length == arguments.length) {
                    return Source.apply(this, arguments);
                } else if (Object.is_function(NameFunction)) {
                    return NameFunction.apply(this, arguments);
                }
            }
        };
    }

    function clone(Sender) {
        var Clon = {};
        jCore.extend(Clon, Sender);
        return Clon;
    }

    jCore.extend(Object, {
        extend: jCore.extend,
        set_type_: set_type_,
        get_type_: get_type_,
        name: name,
        is_string: is_string,
        is_number: is_number,
        is_array: is_array,
        is_function: is_function,
        is_null: is_null,
        is_control: is_control,
        add_method: add_method,
        clone: clone
    });

    jCore.extend(window, { add_method: Object.add_method });

    if (!window.node) {
        var node = {
            ELEMENT_NODE: 1,
            ATTRIBUTE_NODE: 2,
            TEXT_NODE: 3,
            CDATA_SECTION_NODE: 4,
            ENTITY_REFERENCE_NODE: 5,
            ENTITY_NODE: 6,
            PROCESSING_INSTRUCTION_NODE: 7,
            COMMENT_NODE: 8,
            DOCUMENT_NODE: 9,
            DOCUMENT_type__NODE: 10,
            DOCUMENT_FRAGMENT_NODE: 11,
            NOTATION_NODE: 12
        };
        jCore.extend(Object, { node: node });
    }
})();


//String
(function() {

    function rtrim() {
        return this.replace(/\s+$/, "");
    }

    function ltrim() {
        return this.replace(/^\s+/, "");
    }

    function lrtrim() {
        return this.replace(/\s+$/, "").replace(/^\s+/, "");
    }

    function trim_all() {
        var $String = this,
            $Acarreo = $String.split(' '),
            $Result = '';
        for (var i = 0, length = $String.length; i < length; i++)
            if ($String[i] != ' ') {
                $Result += $String[i];
            }
        return $Result;
    }

    function to_array(Selector) {
        return this.split(Selector || '');
    }

    /**
     * Trunca una cadena
     */
    function truncate(len, remplacement) {
        var _len = len || 10,
            _string = remplacement || '';
        if (this.length > _len)
            return this.substring(0, _len) + _string;
        else
            return this;
    }

    function strip_tags() {
        return this.replace(/<\w+(\s+("[^"]*"|'[^']*'|[^>])+)?>|<\/\w+>/gi, '');
    }

    /**
     * Transforma todos los caracteres en minúsculas
     */
    function lower() {
        return this.toLowerCase();
    }

    /**
     * Transforma todos los caracteres en mayusculas
     */
    function upper() {
        return this.toUpperCase();
    }

    /**
     * Transforma la primera letra de la cadena en mayúsculas y el resto en minúsculas
     */
    function capitalize() {
        return this.charAt(0).UCase() + this.substring(1).LCase();
    }

    function lstring(len) {
        if (Isset(len)) {
            if (len <= 0) return this;
            if (len >= this.length) return this;
            return this.toString().substring(0, len);
        }
        return this;
    }

    function rstring(len) {
        if (Isset(len)) {
            if (len <= 0) return this;
            if (len >= this.length) return this;
            return this.toString().substring(this.length - len, this.length);
        } else return this;
    }


    function is_empty() {
        return (/^\s*$/).test(this);
    }

    /**
     * Verifica si el string contine una cadena especifica
     */
    function contains(str) {
        return this.indexOf(str) > -1;
    }

    /**
     * Escapa los caracteres y tags HTML
     */
    function escape_html() {
        return this.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    /**
     * 
     */
    function unescape_html() {
        return this.removeTags().replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&amp;/g, '&');
    }

    /**
     * Agrega tantas veces se necesito un string a otro
     */
    function repeat_string(iterator) {
        return (iterator < 1) ? this : new Array(iterator + 1).join(this).toString();
    }

    /**
     * Verifica si el String es una email válido
     */
    function is_email() {
        if (this.is_empty()) return false;
        var RegExp = (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/);
        return (RegExp.test(this) ? true : false);
    }

    /**
     * Verifica si el string es una fecha válida
     */
    function is_date() {

        var Anio = this.substring(this.lastIndexOf("-") + 1, this.length),
            Mes = this.substring(this.indexOf("-") + 1, this.lastIndexOf("-")),
            Dia = this.substring(0, this.indexOf("-"));

        if (isNaN(Anio) || Anio.length < 4 || parseFloat(Anio) < 1900) {
            return false;
        }
        if (isNaN(Mes) || parseFloat(Mes) < 1 || parseFloat(Mes) > 12) {
            return false;
        }
        if (isNaN(Dia) || parseInt(Dia, 10) < 1 || parseInt(Dia, 10) > 31) {
            return false;
        }
        if (Mes == 4 || Mes == 6 || Mes == 9 || Mes == 11 || Mes == 2) {
            if (Mes == 2 && Dia > 28 || Dia > 30) {
                return false;
            }
        }
    }



    /**
     * Convierte una cadena en Hexadecimal
     */
    function to_hex(dec) {
        var hexadecimal = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F"),
            hexaDec = Math.floor(dec / 16),
            hexaUni = dec - (hexaDec * 16);
        return (hexadecimal[hexaDec] + hexadecimal[hexaUni]);
    }


    /**
     * Transforma una meida de pixel '10px' a un intero. Opcionalemnte le suma otro entero
     */
    function px2int(add) {
        return (new Number(this.replace('px', '')) + (add || 0));
    }

    function addpx(Add) {
        return this.PxToInt(Add) + 'px';
    }

    function encode() {
        return encodeURIComponent(this);
    }

    function decode() {
        return decodeURIComponent(this);
    }

    function len() {
        return this.length;
    }

    Object.extend(String.prototype, {
        rtrim: rtrim,
        ltrim: ltrim,
        lrtrim: lrtrim,
        trim_all: trim_all,
        truncate: truncate,
        to_array: to_array,
        strip_tags: strip_tags,
        lower: lower,
        upper: upper,
        lstring: lstring,
        rstring: rstring,
        capitalize: capitalize,
        is_empty: is_empty,
        contains: contains,
        escape_html: escape_html,
        unescape_html: unescape_html,
        repeat_string: repeat_string,
        is_email: is_email,
        is_date: is_date,
        to_hex: to_hex,
        px2int: px2int,
        addpx: addpx,
        encode: encode,
        decode: decode,
        len: len
    });
})();


/**
 * 
 * @param {*} Directory 
 * @param {*} Type 
 * @param {*} Attributes 
 * @param {*} Parent 
 */
var include = function(Directory, Type, Attributes, Parent) {
    switch (Type.toLowerCase()) {
        case 'script':
            var Lang = 'javascript1.3',
                Type = 'text/javascript';
            if (Attributes) {
                Lang = Attributes.Language ? Attributes.Language : Lang;
                Type = Attributes.Type ? Attributes.Type : Type;
            }
            try {
                var _control = new Element('script', { type: Type, language: Lang, src: Directory }, Parent || Head);
            } catch (ex) { alert(ex); return false; }

            break;

        case 'style':
            var Rel = 'stylesheet',
                Media = 'screen';
            if (Attributes) {
                Rel = Attributes.Rel ? Attributes.Rel.toLowerCase() : Rel;
                Media = Attributes.Media ? Attributes.Media.toLowerCase() : Media;
            }
            var _control = new Element('link', { href: Directory, rel: Rel, media: Media }, Parent || Head);
            break;
    }
    return _control;
};

/**
 * Verifica si no existe el archivo en el documento y lo incuye en el.
 * @param {String} Directory Directorio del archivo a incluir.
 * @param {String} Type Tipo de archivo. Por defecto "script".
 * @param {Object} (Opcional) Attributes Atributos de archivo. Por defecto "{}".
 * @param {String} (Opcional) Parent Establece en que nodo se agregará el vinculo. Por defecto "head".
 */
var include_once = function(Directory, Type, Attributes, Parent) {
    var _Includes = tag_name(Type);
    _type_ = Type.toLowerCase();
    for (var i = 0; i < _Includes.lenght; i++) {
        if (_Includes[i].src != null && _Includes[i].src.indexOf(Type) != -1) {
            return false;
        }
    }
    return Include(Directory, Type, Attributes);
};



function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}



function get_date(larga) {
    var today = new Date();
    var dia = today.getDate();
    var mes = today.getMonth() + 1; //January is 0!
    var anio = today.getFullYear();
    var hora = today.getHours();
    var minutos = today.getMinutes();

    var dn = "AM";

    if (hora > 12) {
        dn = "PM";
        hora = hora - 12;
    }
    if (hora == 0) {
        hora = 12;
    }

    if (hora < 10) {
        hora = '0' + hora;
    }

    if (minutos < 10) {
        minutos = '0' + minutos;
    }


    if (dia < 10) {
        dia = '0' + dia
    }

    if (mes < 10) {
        mes = '0' + mes
    }

    if (hora) {
        return dia + '/' + mes + '/' + anio + ' ' + hora + ':' + minutos + ' ' + dn;
    } else {
        return dia + '/' + mes + '/' + anio;
    }

}



function notify(text, type, time) {
    time = time ? time : 7000;

    $().toastmessage('showToast', {
        text: text ? text : '',
        position: 'top-right',
        sticky: false,
        stayTime: time,
        type: type ? type : 'success',
    });
}

function messagebox(title, message, type) {

    bootbox.alert({
        message: '</br><p>' + message + '</br></p>',
        size: 'small',
    });
}





