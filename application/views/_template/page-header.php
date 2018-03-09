
            <!-- start: section role="main" class="content-body" -->
            <section role="main" class="content-body">
               <header class="page-header">
                  <h2><?php echo $page_title; ?></h2>
               
                  <div class="right-wrapper pull-right">
                     <ol class="breadcrumbs">
                        <li>
                           <a href="<?php echo ROOT_RELATIVE_PATH; ?>"><i class="fa fa-home"></i></a>
                        </li>
                        <?php
                        foreach ($breadcrumb_items as $list_item) {
                           echo "<li><span>" . $list_item . "</span></li>\n";
                        }
                        ?>
                     </ol>
               
                     <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
                  </div>
               </header>