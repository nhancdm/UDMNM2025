<?php
/*
Template Name: Trangchu
*/
get_header();
?>
<div id="slider">
  <div class="container-fluid">
      <div class="row">
        <div class="col-12">
         <?php echo do_shortcode('[metaslider id = "305"]')?>
        </div>
      </div>
  </div>
</div>
<div id="content">
  <div class="row">
        <div class="container">
         <div class="col-12">
          <div class="chuongtrinhdaotao">
   <h2 class="text-center">Chương Trình Đào Tạo</h2>
   <div class="row">
      <?php 
      $args = array(
         'category__in' => array(27),
         'posts_per_page' => 6,
      );
      $my_query = new WP_Query($args);
      if($my_query->have_posts()) {
         while($my_query->have_posts()) {
            $my_query->the_post();
      ?>
         <div class="col-12 col-sm-6 col-lg-3">
            <div class="ctdt-box">
               <div class="ctdt-thumb">
                  <?php the_post_thumbnail('medium'); ?>
               </div>
               <h5 class="ctdt-title"><?php the_title(); ?></h5>
               <p class="ctdt-excerpt"><?php the_excerpt(); ?></p>
            </div>
         </div>
      <?php
         }
      }
      wp_reset_postdata();
      ?>
   </div>
</div>

         </div>
      </div>
  </div>
</div>

	<section class="text-center py-5 bg-light">
  <div class="container">
    <h2 class="fw-bold mb-3 text-primary">Đào Tạo Kỹ Năng</h2>
    <p class="text-muted fs-5">
      Trang bị cho học viên học lái xe ô tô hiểu và nắm rõ luật an toàn giao thông cũng như cách xử lý các tình huống khi tham gia giao thông.
    </p>
  </div>
</section>

<div class="container py-5">
  <div class="row g-4">
    <?php $daotao = array('dao_tao_01','dao_tao_2','dao_tao_3','dao_tao_4'); ?>
    <?php foreach($daotao as $row_daotao): ?>
      <?php $values = get_field($row_daotao); ?>
      <div class="col-md-4 col-sm-6">
        <div class="product-card card h-100 shadow-sm border-0">
          <img src="<?php echo $values['anh_dai_dien']; ?>" class="card-img-top rounded-top" alt="Hình ảnh đào tạo">
          <div class="card-body d-flex flex-column">
            <div class="card-text mb-3">
              <?php echo $values['noi_dung']; ?>
            </div>
            <a href="#" class="btn btn-outline-primary mt-auto">Tìm hiểu</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>



<div id="content">
 <div class="container">
   <div class="row">
      <div>
         
      </div>
   </div>
 </div>
</div>
<?php
get_footer();
