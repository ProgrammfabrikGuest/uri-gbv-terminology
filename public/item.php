<?php

include_once 'utils.php';

row('URI', uri_link($JSKOS, $JSKOS->uri, false), 'link');

row_list('Notation', $JSKOS, 'notation',
    function ($n) { return '<code>'.htmlspecialchars($n).'</code>'; },
    'bookmark'
);

row_list('Identifier', $JSKOS, 'identifier',
    function ($id) {
        if (preg_match('/^https?:/', $id)) {
            $id = uri_link((object)['uri'=>$id], $id, false);
        }
        return "<code>$id</code>";
    }, 'star'
);

$labelTypes = [
    'altLabel'      => ['Label', null],
    'hiddenLabel'   => ['Suchbegriffe', null],
    'scopeNote'     => ['Hinweis', null],
    'definition'    => ['Definition', null],
    'example'       => ['Beispiel', null],
    'editorialNote' => ['Berarbeitungshinweis', null],
    'changeNote'    => ['Änderungshinweis', null]
];

foreach ($labelTypes as $type => $rel) {
    if (!$JSKOS->$type) continue;
    list ($name, $icon) = $rel;

    $labels = $JSKOS->$type;
    if ($labels->isEmpty()) continue;

    $labelList = [];
    foreach ($JSKOS->$type as $lang => $list) {
        foreach ($list as $s) {
            $s = htmlspecialchars($s);
            if ($lang != $LANGUAGE) $s .= "<sup>$lang</sup>";
            $labelList[] = $s;
        }
    }

    if (!$labels->isClosed()) {
        $labelList[] = "&#x2026;";
    }

    row($name, implode('<br>', $labelList), $icon);
}

row('URL', formatted('<a href="%s">%s</a>', $JSKOS->url, $JSKOS->url), 'home');

# TODO: type, partOf
# TODO: subject, subjectOf, depiction
