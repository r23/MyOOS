{include file="myoos/system/_header.tpl"}
    <section id="featured">
        {$featured}
    </section>	
	
{if $message}
    {foreach item=info from=$message}
        {include file="myoos/system/_message.tpl"}
    {/foreach}
{/if}

    <section class="callaction">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h2 class="text-color">Jemand musste Josef K. verleumdet haben.!</h2>
                <p class="text-muted">Jemand musste Josef K. verleumdet haben, denn ohne dass er etwas Böses getan hätte, wurde er eines Morgens verhaftet. »Wie ein Hund! « sagte er, es war, als sollte die Scham ihn überleben.</p>
            </div>
            <div class="col-md-2">
                <div class="cta floatright">
                    <a href="#" class="btn btn-color btn-lg">Buy Now!</a>
                </div>
            </div>			
        </div>
    </div>
    </section>

    <section id="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {$lang.text_main}
            </div>
        </div>
    </div>
    </section>	

{$new_spezials}
{$new_products}
{$upcoming_products}

{include file="myoos/system/_footer.tpl"}