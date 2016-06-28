<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>

<?
//if($USER->IsAuthorized()) header("Location: /index.php");
?>

<body class="external-page sb-l-c sb-r-c">

  <!-- Start: Main -->
  <div id="main" class="animated fadeIn">

    <!-- Start: Content-Wrapper -->
    <section id="content_wrapper">

      <!-- begin canvas animation bg -->
      <div id="canvas-wrapper">
        <canvas id="demo-canvas"></canvas>
      </div>

      <!-- Begin: Content -->
      <section id="content" class="">

        <div class="admin-form theme-info mw700" style="margin-top: 3%;" id="login1">

          <div class="row mb15 table-layout">

            <div class="col-xs-6 va-m pln">
              <a href="dashboard.html" title="Return to Dashboard">
				  <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/logos/logo_white.png" title="DHI API" class="img-responsive w250">
              </a>
            </div>

            <div class="col-xs-6 text-right va-b pr5">
              <div class="login-links">
				<a href="/login/" class="" title="Sign In">Войти</a>
                <span class="text-white"> | </span>
				<a href="/registration/" class="active" title="Register">Регистрация</a>
              </div>

            </div>

          </div>

          <div class="panel panel-info mt10 br-n">

			  <?/*<div class="panel-heading heading-border bg-white">
              <div class="section row mn">
                <div class="col-sm-4">
                  <a href="#" class="button btn-social facebook span-left mr5 btn-block">
                    <span>
                      <i class="fa fa-facebook"></i>
                    </span>Facebook</a>
                </div>
                <div class="col-sm-4">
                  <a href="#" class="button btn-social twitter span-left mr5 btn-block">
                    <span>
                      <i class="fa fa-twitter"></i>
                    </span>Twitter</a>
                </div>
                <div class="col-sm-4">
                  <a href="#" class="button btn-social googleplus span-left btn-block">
                    <span>
                      <i class="fa fa-google-plus"></i>
                    </span>Google+</a>
                </div>
              </div>
			  </div>*/?>

			<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" id="account2">
				<input type="hidden" id="login" name="REGISTER[LOGIN]" value="<?=$_REQUEST['REGISTER']['LOGIN']?>"/>
			<?
			if($arResult["BACKURL"] <> ''):
			?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?
			endif;
			?>

              <div class="panel-body p25 bg-light">
              	
                <div class="section-divider mt10 mb40">
                  <span><?=GetMessage("AUTH_REGISTER")?></span>
                </div>
                <!-- .section-divider -->

                <?if (count($arResult["ERRORS"]) > 0):?>

                	<div id="primary_inventory_error_block" class="alert alert-danger" style="display: block;">
                	<?
                		foreach ($arResult["ERRORS"] as $key => $error)
							if (intval($key) == 0 && $key !== 0) 
								$arResult["ERRORS"][$key] = ' - ' . str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

						echo implode("<br />", $arResult["ERRORS"]);
	              	?>
	              	</div>
					
				<?endif?>
                
                <div class="section row">
                  <div class="col-md-6">
                    <label for="firstname" class="field prepend-icon">
                      <input type="text" name="REGISTER[NAME]" id="firstname" class="gui-input" 
                      	value='<?=$_REQUEST["REGISTER"]["NAME"]?>' placeholder="<?=GetMessage('REGISTER_FIELD_NAME')?>...">
                      <label for="firstname" class="field-icon">
                        <i class="fa fa-user"></i>
                      </label>
                    </label>
                  </div>
                  <!-- end section -->

                  <div class="col-md-6">
                    <label for="lastname" class="field prepend-icon">
                      <input type="text" name="REGISTER[SECOND_NAME]" id="lastname" class="gui-input"
                      value='<?=$_REQUEST["REGISTER"]["SECOND_NAME"]?>' placeholder="<?=GetMessage('REGISTER_FIELD_LAST_NAME')?>...">
                      <label for="lastname" class="field-icon">
                        <i class="fa fa-user"></i>
                      </label>
                    </label>
                  </div>
                  <!-- end section -->
                </div>
                <!-- end .section row section -->

                <div class="section">
                  <label for="email" class="field prepend-icon">
                    <input type="email" name="REGISTER[EMAIL]" id="email" class="gui-input" 
                    value='<?=$_REQUEST["REGISTER"]["EMAIL"]?>' placeholder="<?=GetMessage('REGISTER_FIELD_EMAIL')?>">
                    <label for="email" class="field-icon">
                      <i class="fa fa-envelope"></i>
                    </label>
                  </label>
                </div>
                <!-- end section -->

                <div class="section">
                  <label for="password" class="field prepend-icon">
                    <input type="password" name="REGISTER[PASSWORD]" id="password" class="gui-input" placeholder="<?=GetMessage('REGISTER_FIELD_PASSWORD')?>">
                    <label for="password" class="field-icon">
                      <i class="fa fa-unlock-alt"></i>
                    </label>
                  </label>
                </div>
                <!-- end section -->

                <div class="section">
                  <label for="confirmPassword" class="field prepend-icon">
                    <input type="password" name="REGISTER[CONFIRM_PASSWORD]" id="confirmPassword" class="gui-input" placeholder="<?=GetMessage('REGISTER_FIELD_CONFIRM_PASSWORD')?>">
                    <label for="confirmPassword" class="field-icon">
                      <i class="fa fa-lock"></i>
                    </label>
                  </label>
                </div>
                <!-- end section -->

              </div>
              <!-- end .form-body section -->
              <div class="panel-footer clearfix">
                <input type="submit" name="register_submit_button" class="button btn-primary pull-right" value="<?=GetMessage("AUTH_REGISTER")?>">
              </div>
              <!-- end .form-footer section -->
            </form>
          </div>
        </div>

      </section>
      <!-- End: Content -->

    </section>
    <!-- End: Content-Wrapper -->

  </div>
  <!-- End: Main -->

	<!-- BEGIN: PAGE SCRIPTS -->

  <!-- CanvasBG Plugin(creates mousehover effect) -->
  <?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/vendor/plugins/canvasbg/canvasbg.js');?>

  <!-- Page Javascript -->
  <script type="text/javascript">
  jQuery(document).ready(function() {
    "use strict";
    // Init Theme Core      
    Core.init();

    // Init Demo JS
    Demo.init();

    // Init CanvasBG and pass target starting location
    CanvasBG.init({
      Loc: {
        x: window.innerWidth / 2.1,
        y: window.innerHeight / 4.2
      },
    });

    $("input[type=submit]").hover(function(){ $("#login").val($("#email").val()); }, function(){});

  });
  </script>

  <!-- END: PAGE SCRIPTS -->
</form>