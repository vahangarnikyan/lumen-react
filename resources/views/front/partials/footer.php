<?php
  use App\Facades\Data;
  use App\Menu;
?>
  </div>
    <div class="site-footer">
      <div class="container">
        <div class="d-flex justify-content-between flex-wrap align-items-center">
          <div>
            <span><?php echo Data::getFooter(); ?></span>
          </div>
          <div>
            <ul class="footer-menu">
              <?php $menus = Data::getMenus(Menu::$MENU_TYPE_FOOTER);
                foreach ($menus as $menu) : 
              ?>
                <li>
                  <a href="<?php echo $menu->locale_url ?>">
                    <?php echo $menu->locale_title; ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
          <div>
            <div class="d-flex flex-wrap align-items-center">
              <div class="credit">
                @2020 <?php echo trans('app.all_rights_reserved'); ?> Ganagratis.com
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo url('/dist/front.js') ?>"></script>
</body>
</html>