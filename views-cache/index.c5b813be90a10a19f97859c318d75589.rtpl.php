<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="container">
    <div class="row">
        <form action="/products" class="d-flex" role="search">
            <div class="input-group input-group-lg pb-3 pt-3" style="width: 100%;">
                <input type="search" name="search" class="form-control border border-dark" placeholder="Qual produto deseja hoje?" value="">
                <button type="submit" class="btn btn-dark" style="width: 5%;"><i class="fa fa-search"></i></button>
            </div>
        </form>
    </div>
</div>

    <!-- Slider -->
<div class="container">
    <div class="row">
        <div id="carouselExampleAutoplaying" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php $counter1=-1;  if( isset($productsSlider) && ( is_array($productsSlider) || $productsSlider instanceof Traversable ) && sizeof($productsSlider) ) foreach( $productsSlider as $key1 => $value1 ){ $counter1++; ?>
                <?php if( $key1 == 0 ){ ?>
                <div class="carousel-item active">
                <?php }else{ ?>
                <div class="carousel-item">
                <?php } ?>
                    <img src="<?php echo htmlspecialchars( $value1["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="rounded mx-auto d-block w-50" alt="Product">
                    <div class="d-none d-md-block" style="text-align: center;">
                        <h4><strong><?php echo htmlspecialchars( $value1["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></strong></h4>
                        <h5>Descrição (Não tem ainda)</h5>
                        <a class="caption button-radius" href="/cart/<?php echo htmlspecialchars( $value1["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add"><span class="icon"></span>Comprar</a>
                    </div>
                </div>
                <?php } ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="promo-area">
<div class="zigzag-bottom"></div>
<div class="container">
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="single-promo promo1">
                <i class="fa fa-refresh"></i>
                <p>1 ano de garantia</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="single-promo promo2">
                <i class="fa fa-truck"></i>
                <p>Frete grátis</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="single-promo promo3">
                <i class="fa fa-lock"></i>
                <p>Pagamento seguro</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="single-promo promo4">
                <i class="fa fa-gift"></i>
                <p>Novos produtos</p>
            </div>
        </div>
    </div>
</div>
</div> <!-- End promo area -->

<div class="maincontent-area">
<div class="zigzag-bottom"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="latest-product">
                <h2 class="section-title">Produtos</h2>
                <div class="product-carousel">
                    <?php $counter1=-1;  if( isset($products) && ( is_array($products) || $products instanceof Traversable ) && sizeof($products) ) foreach( $products as $key1 => $value1 ){ $counter1++; ?>
                    <div class="single-product">
                        <div class="product-f-image">
                            <img src="<?php echo htmlspecialchars( $value1["desphoto"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" alt="">
                            <div class="product-hover">
                                <a href="/cart/<?php echo htmlspecialchars( $value1["idproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/add" class="add-to-cart-link"><i class="fa fa-shopping-cart"></i> Comprar</a>
                                <a href="/products/<?php echo htmlspecialchars( $value1["desurl"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="view-details-link"><i class="fa fa-link"></i> Ver Detalhes</a>
                            </div>
                        </div>
                        
                        <h2><a href="/products/<?php echo htmlspecialchars( $value1["desurl"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["desproduct"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></h2>
                        
                        <div class="product-carousel-price">
                            <ins>RS<?php echo formatPrice($value1["vlprice"]); ?></ins>
                        </div> 
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- End main content area -->

<div class="brands-area">
<div class="zigzag-bottom"></div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="brand-wrapper">
                <div class="brand-list">
                    <img src="/res/site/img/brand1.png" alt="">
                    <img src="/res/site/img/brand2.png" alt="">
                    <img src="/res/site/img/brand3.png" alt="">
                    <img src="/res/site/img/brand4.png" alt="">
                    <img src="/res/site/img/brand5.png" alt="">
                    <img src="/res/site/img/brand6.png" alt="">
                    <img src="/res/site/img/brand1.png" alt="">
                    <img src="/res/site/img/brand2.png" alt="">                            
                </div>
            </div>
        </div>
    </div>
</div>
</div> <!-- End brands area -->