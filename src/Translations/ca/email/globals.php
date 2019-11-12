<?php

return [
    // Email subject
    'notify-ticket-category'           => 'Tiquets :name',

    'notify-created-by'                => 'Nou de :name',
    'notify-closed-by'                 => 'tancat per :agent',
    'notify-status-updated-by'         => 'estat canviat per :agent',
    'notify-ticket-closed-by'          => 'tiquet resolt per :agent',
    'notify-assigned-to-you-by'        => 'assignat per :agent',
    'notify-new-reply-by'              => 'nou comentari de :name',
    'notify-new-note-by'               => 'nova nota interna de :name',
    'notify-note-updated-by'           => 'nota actualitzada per :name',

    // Body: General
    'salutation'          => 'Benvolgut/da,',
    'complimentary_close' => 'Atentament,',

    // Body: Resumed message
    'agent_new_ticket'  => '<b style="color: #fa4f10;">:agent</b> ha creat aquest tiquet i te l\'ha assignat.',
    'user_new_ticket'   => '<b style="color: #fa4f10;">:user</b> ha creat aquest tiquet i se t\'ha assignat degut a l\'assignació automàtica de la categoria',
    'closed_ticket'     => '<b style="color: #fa4f10;">:user</b> ha tancat aquest tiquet.',
    'updated_status'    => '<b style="color: #fa4f10;">:user</b> ha canviat l\'estat d\'aquest tiquet.',
    'updated_agent'     => '<b style="color: #fa4f10;">:user</b> t\'ha assignat aquest tiquet.',

    'added_reply'        => '<b style="color: #fa4f10;">:user</b> ha afegit una resposta a aquest tiquet.',
    'new_reply_title'    => 'Nova resposta',
    'added_note'         => '<b style="color: #fa4f10;">:user</b> ha afegit una <b>nota interna</b> a aquest tiquet.',
    'new_note_title'     => 'Nova nota interna',
    'updated_agent_note' => '<b style="color: #fa4f10;">:user</b> ha actualitzat una <b>nota interna</b> d\' aquest tiquet.',
    'updated_your_note'  => '<b style="color: #fa4f10;">:user</b> ha actualitzat una nota interna <b>teva</b> d\' aquest tiquet.',
    'updated_other_note' => '<b style="color: #fa4f10;">:user</b> ha actualitzat una <b>nota interna</b> d\' aquest tiquet creada per <b>:other</b>.',
    'original_note_title'=> 'Nota original',
    'updated_note_title' => 'Nota actualitzada',

    // Body: Ticket link
    'view-ticket-title'   => 'Fes clic aquí per veure el tiquet.',
    'view-ticket-text'    => 'Veure el tiquet',
];
