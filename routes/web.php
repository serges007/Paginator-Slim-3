<?php

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

$app->get('/products', function ($request, $response) {
    $dbProducts = $this->db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

    $page = $request->getParam('page', 1);
    
    $perPage = $request->getParam('perPage', 3);

//    dump(($page - 1) * $perPage);
//    die();    
    
    $products = new LengthAwarePaginator(
            array_slice($dbProducts, ($page - 1) * $perPage ,$perPage),
            count($dbProducts),
            $perPage,
            $page,
            ['path' => $request->getUri()->getPath(), 'query' => $request->getParams()] 
    );
    
    return $this->view->render($response, 'users/index.twig', compact('products'));
});


$app->get('/api/products', function ($request, $response) {
    $dbProducts = $this->db->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

    $page = $request->getParam('page', 1);
    $perPage = $request->getParam('perPage', 3);

    $products = new LengthAwarePaginator(
        $slicedProducts = array_slice($dbProducts, ($page - 1) * $perPage, $perPage),
        count($dbProducts),
        $perPage,
        $page,
        ['path' => $request->getUri()->getPath(), 'query' => $request->getParams()]
    );

    return $response->withJson([
        'data' => $slicedProducts
//        'meta' => [
//            'pagination' => array_except($users->toArray(), ['data'])
//        ]
    ]);
});


$app->get('/eloquent/products', function ($request, $response) {
    $products = Product::paginate(3)->appends($request->getParams());

    return $this->view->render($response, 'users/index.twig', compact('products'));
});


$app->get('/eloquent/api/products', function ($request, $response) {
    $users = Product::paginate(3)->appends($request->getParams());

    return $response->withJson([
        'data' => $users->getCollection(),
        'meta' => [
            'pagination' => array_except($users->toArray(), ['data'])
        ]
    ]);
});


