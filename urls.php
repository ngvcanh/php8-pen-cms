<?php

function handler($request){
    return render($request, 'main.html');
}

function handler_path($request, $id){
    return render($request, 'demo.html', [ 'id' => $id ]);
}

$urlpatterns = [
    path('', 'handler'),
    path('demo/<str:id>/', 'handler_path'),
    path('cms/', include_app('cms.urls'))
];