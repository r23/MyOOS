{include file="myoos/system/_header.tpl"}
    <!-- Wrapper -->
    <div class="wrapper">
    <section id="inner-headline">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="inner-heading">
                    <h2>{$heading_title}</h2>
                </div>
            </div>
            <div class="col-md-8">
                {$breadcrumb}
            </div>
        </div>
    </div>
    </section>      
{if $message}
    {foreach item=info from=$message}
        {include file="myoos/system/_message.tpl"}
    {/foreach}
{/if} 
    <section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form class="form-horizontal" role="form" name="password_forgotten" action="{html_get_link connection=SSL}" method="post">
                    {if $oos_session_name}<input type="hidden" name="{$oos_session_name}" value="{$oos_session_id}">{/if}
                    {if $formid}<input type="hidden" name="formid" value="{$formid}">{/if}
                    <input type="hidden" name="action" value="process">
                    <input type="hidden" name="content" value="{$contents.password_forgotten}">			
					
                    <div class="form-group">
                        <label for="eingabefeldEmail3" class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" placeholder="Enter email" data-original-title="" title="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-color">{$lang.button_continue}</button>
                        </div>
                    </div>					
                </form>			
            </div>
        </div>	
    </div>
    </section>
    </div> <!-- / .wrapper -->	
{include file="myoos/system/_footer.tpl"}        