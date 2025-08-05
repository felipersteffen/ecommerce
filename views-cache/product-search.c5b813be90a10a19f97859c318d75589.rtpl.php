<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="row">
        <div class="container">
            <form action="/products" class="d-flex" role="search">
                <div class="input-group input-group pb-3 pt-3" style="width: 100%;">
                    <input type="search" name="search" class="form-control" placeholder="Search" value="<?php echo htmlspecialchars( $search, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    <button type="submit" class="btn btn-outline" style="width: 5%;"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
</div>

<div class="row row-cols-1 row-cols-md-4 g-4">
    <?php $counter1=-1;  if( isset($products) && ( is_array($products) || $products instanceof Traversable ) && sizeof($products) ) foreach( $products as $key1 => $value1 ){ $counter1++; ?>

    <div class="col">
        <div class="card border-dark h-100">
            <img src="<?php echo htmlspecialchars( $value1["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="card-img-top img-thumbnail" alt="">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars( $value1["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h5>
                <h6 class="card-subtitle mb-2 text-body-secondary">Categories: <?php echo htmlspecialchars( $value1["categories"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h6>
                <a class="btn btn-dark" data-quantity="1" data-product_sku="" data-product_id="70" rel="nofollow" href="/cart/<?php echo htmlspecialchars( $value1["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add">Comprar</a>
            </div>
        </div>
    </div>
    <?php } ?>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="product-pagination text-center">
            <nav>
                <ul class="pagination justify-content-center">
                <?php $counter1=-1;  if( isset($pages) && ( is_array($pages) || $pages instanceof Traversable ) && sizeof($pages) ) foreach( $pages as $key1 => $value1 ){ $counter1++; ?>

                <?php if( $activePage == $value1["text"] ){ ?>

                <li class="page-item active">
                <?php }else{ ?>

                <li class="page-item">
                <?php } ?>

                    <a class="page-link" href="<?php echo htmlspecialchars( $value1["href"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["text"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a>
                </li>
                <?php } ?>

                </ul>
            </nav>                        
        </div>
    </div>
</div>
    
</div>