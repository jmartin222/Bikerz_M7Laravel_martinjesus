<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validació de llengua
    |--------------------------------------------------------------------------
    |
    | Les següents línies de llengua contenen els missatges d'error per defecte utilitzats per
    | la classe validadora. Algunes d'aquestes regles tenen múltiples versions tales
    | com les regles de mida. Sentiu-vos lliures de modificar cada un d'aquests missatges aquí.
    |
    */

    'accepted' => 'El camp :attribute ha de ser acceptat.',
    'accepted_if' => 'El camp :attribute ha de ser acceptat quan :other és :value.',
    'active_url' => 'El camp :attribute no és una URL vàlida.',
    'after' => 'El camp :attribute ha de ser una data després de :date.',
    'after_or_equal' => 'El camp :attribute ha de ser una data després o igual a :date.',
    'alpha' => 'El camp :attribute només pot contenir lletres.',
    'alpha_dash' => 'El camp :attribute només pot contenir lletres, nombres i guions.',
    'alpha_num' => 'El camp :attribute només pot contenir lletres i nombres.',
    'array' => 'El camp :attribute ha de ser un array.',
    'before' => 'El camp :attribute ha de ser una data abans de :date.',
    'before_or_equal' => 'El camp :attribute ha de ser una data abans o igual a :date.',
    'between' => [
        'numeric' => 'El camp :attribute ha d\'estar entre :min - :max.',
        'file' => 'El camp :attribute ha d\'estar entre :min - :max kilobytes.',
        'string' => 'El camp :attribute ha d\'estar entre :min - :max caràcters.',
        'array' => 'El camp :attribute ha de tenir entre :min i :max elements.',
    ],
    'boolean' => 'El camp :attribute ha de ser cert o fals.',
    'confirmed' => 'El camp de confirmació de :attribute no coincideix.',
    'current_password' => 'La contrasenya actual no és correcta',
    'date' => 'El camp :attribute no és una data vàlida.',
    'date_equals' => 'El camp :attribute ha de ser una data igual a :date.',
    'date_format' => 'El camp :attribute no correspon amb el format :format.',
    'declined' => 'El camp :attribute ha de marcar com a rebutjat.',
    'declined_if' => 'El camp :attribute ha de marcar com a rebutjat quan :other és :value.',
    'different' => 'Els camps :attribute i :other han de ser diferents.',
    'digits' => 'El camp :attribute ha de ser de :digits dígits.',
    'digits_between' => 'El camp :attribute ha de tenir entre :min i :max dígits.',
    'dimensions' => 'El camp :attribute no té una dimensió vàlida.',
    'distinct' => 'El camp :attribute té un valor duplicat.',
    'doesnt_end_with' => 'El camp :attribute no pot finalitzar amb un dels següents valors: :values.',
    'doesnt_start_with' => 'El camp :attribute no pot començar amb un dels següents valors: :values.',
    'email' => 'El format del camp :attribute no és vàlid.',
    'ends_with' => 'El camp :attribute ha de finalitzar amb algun dels valors: :values.',
    'enum' => 'El camp seleccionat a :attribute no és vàlid.',
    'exists' => 'El camp seleccionat :attribute no és vàlid.',
    'file' => 'El camp :attribute ha de ser un arxiu.',
    'filled' => 'El camp :attribute és necessari.',
    'gt' => [
        'numeric' => 'El camp :attribute ha de ser major que :value.',
        'file' => 'El camp :attribute ha de ser major que :value kilobytes.',
        'string' => 'El camp :attribute ha de ser major que :value caràcters.',
        'array' => 'El camp :attribute pot tenir fins a :value elements.',
    ],
    'gte' => [
        'numeric' => 'El camp :attribute ha de ser major o igual que :value.',
        'file' => 'El camp :attribute ha de ser major o igual que :value kilobytes.',
        'string' => 'El camp :attribute ha de ser major o igual que :value caràcters.',
        'array' => 'El camp :attribute pot tenir :value elements o més.',
    ],
    'image' => 'El camp :attribute ha de ser una imatge.',
    'in' => 'El camp seleccionat :attribute no és vàlid.',
    'in_array' => 'El camp :attribute no existeix a :other.',
    'integer' => 'El camp :attribute ha de ser un enter.',
    'ip' => 'El camp :attribute ha de ser una adreça IP vàlida.',
    'ipv4' => 'El camp :attribute ha de ser una adreça IPv4 vàlida.',
    'ipv6' => 'El camp :attribute ha de ser una adreça IPv6 vàlida.',
    'json' => 'El camp :attribute ha de ser una cadena JSON vàlida.',
    'lt' => [
        'numeric' => 'El camp :attribute ha de ser menor que :max.',
        'file' => 'El camp :attribute ha de ser menor que :max kilobytes.',
        'string' => 'El camp :attribute ha de ser menor que :max caràcters.',
        'array' => 'El camp :attribute pot tenir fins a :max elements.',
    ],
    'lte' => [
        'numeric' => 'El camp :attribute ha de ser menor o igual a :max.',
        'file' => 'El camp :attribute ha de ser menor o igual a :max kilobytes.',
        'string' => 'El camp :attribute ha de ser menor o igual a :max caràcters.',
        'array' => 'El camp :attribute no pot tenir més de :max elements.',
    ],
    'mac_address' => 'El camp :attribute ha de ser una adreça MAC vàlida.',
    'max' => [
        'numeric' => 'El camp :attribute ha de ser menor a :max.',
        'file' => 'El camp :attribute ha de ser menor a :max kilobytes.',
        'string' => 'El camp :attribute ha de ser menor a :max caràcters.',
        'array' => 'El camp :attribute pot tenir fins a :max elements.',
    ],
    'max_digits' => 'El camp :attribute no pot superar els :max dígits.',
    'mimes' => 'El camp :attribute ha de ser un fitxer de tipus: :values.',
    'mimetypes' => 'El camp :attribute ha de ser un fitxer de tipus: :values.',
    'min' => [
        'numeric' => 'El camp :attribute ha de tenir com a mínim :min.',
        'file' => 'El camp :attribute ha de tenir com a mínim :min kilobytes.',
        'string' => 'El camp :attribute ha de tenir com a mínim :min caràcters.',
        'array' => 'El camp :attribute ha de tenir com a mínim :min elements.',
    ],
    'min_digits' => 'El camp :attribute ha de ser com a mínim de :min dígits.',
    'multiple_of' => 'El camp :attribute ha de ser un múltiple de :value.',
    'not_in' => 'El camp :attribute seleccionat és invàlid.',
    'not_regex' => 'El format del camp :attribute no és vàlid.',
    'numeric' => 'El camp :attribute ha de ser un nombre.',
    'password' => [
        'letters' => 'El camp :attribute ha de contenir com a mínim una lletra.',
        'mixed' => 'El camp :attribute ha de contenir com a mínim una lletra majúscula i una minúscula.',
        'numbers' => 'El camp :attribute ha de contenir com a mínim un nombre.',
        'symbols' => 'El camp :attribute ha de contenir com a mínim un símbol.',
        'uncompromised' => 'El valor del camp :attribute apareix en alguna filtració de dades. Si us plau, indica un valor diferent.',
    ],
    'present' => 'El camp :attribute ha de ser-hi present.',
    'prohibited' => 'El camp :attribute no està permès.',
    'prohibited_if' => 'El camp :attribute no està permès quan :other és :value.',
    'prohibited_unless' => 'El camp :attribute no està permès si :other no està en :values.',
    'prohibits' => 'El camp :attribute no permet que :other estigui present.',
    'regex' => 'El format del camp :attribute no és vàlid.',
    'required' => 'El camp :attribute és requerit.',
    'required_array_keys' => 'El camp :attribute ha de contenir entrades per: :values.',
    'required_if' => 'El camp :attribute és requerit quan el camp :other és :value.',
    'required_unless' => 'El camp :attribute és requerit a menys que :other estigui present en :values.',
    'required_with' => 'El camp :attribute és requerit quan :values està present.',
    'required_with_all' => 'El camp :attribute és requerit quan :values està present.',
    'required_without' => 'El camp :attribute és requerit quan :values no està present.',
    'required_without_all' => 'El camp :attribute és requerit quan cap :values està present.',
    'same' => 'El camp :attribute i :other han de coincidir.',
    'size' => [
        'numeric' => 'El camp :attribute ha de ser :size.',
        'file' => 'El camp :attribute ha de tenir :size kilobytes.',
        'string' => 'El camp :attribute ha de tenir :size caràcters.',
        'array' => 'El camp :attribute ha de contenir :size elements.',
    ],
    'starts_with' => 'El :attribute ha de començar amb un dels següents valors :values',
    'string' => 'El camp :attribute ha de ser una cadena.',
    'timezone' => 'El camp :attribute ha de ser una zona vàlida.',
    'unique' => 'El camp :attribute ja ha estat pres.',
    'uploaded' => 'El camp :attribute no ha pogut ser carregat.',
    'url' => 'El format de :attribute no és vàlid.',
    'uuid' => 'El :attribute ha de ser un UUID vàlid.',

    /*
    |--------------------------------------------------------------------------
    | Validació de la llengua personalitzada
    |--------------------------------------------------------------------------
    |
    | Aquí pots especificar missatges de validació personalitzats per atributs utilitzant la
    | convenció "attribute.rule" per nomenar les línies. Això fa que sigui ràpid
    | especificar una línia de llengua personalitzada específica per una regla d'atribut donada.
    |
    */

    'custom' => [
        'punt_sortida' => [
            'unique' => 'El punt de sortida a la data indicada ya ha estat pres.',
        ],
    ],

    /*
     |------------------------------------------------- -------------------------
     | Atributs de validació personalitzats
     |------------------------------------------------- -------------------------
     |
     | Les línies d'idioma següents s'utilitzen per intercanviar les adreces d'interès de posició d'atribut.
     | amb una mica més fàcil de llegir, com ladreça de correu electrònic.
     | de “email”. Això simplement ens ajuda a fer els missatges una mica més nets.
     |
     */

    'attributes' => [],

];
