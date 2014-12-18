    <!-- newsletter -->
    <div class="subscribe">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h2>{$lang.block_newsletters_subscribe}</h2>
                </div>  
                <div class="col-md-4">
				<form role="form" name="subscribe" action="{html_get_link connection=SSL}" method="post">
					{if $mySystem.sed}
						<input type="hidden" name="{$mySystem.session_name}" value="{$mySystem.session_id}">
					{/if}
						<input type="hidden" name="action" value="process">
						<input type="hidden" name="content" value="{$filename.newsletters}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="{$lang.block_newsletters_placeholder}">
                        <span class="input-group-btn">
                            <button class="btn" type="button"><i class="fa fa-envelope-o"></i></button>
                        </span>
                    </div>    
                </div>
            </div>
        </div><!--end container-->
    </div>
    <!-- end newsletter ===-->