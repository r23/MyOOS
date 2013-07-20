if(jQuery)                                                  // Check if jQuery exists
{
    jQuery(document).ready(
        function($)
        {
            // Allow only specific characters to be entered in password field
            $("#wpbb_deactivation_password").keyup(
                function()
                {
                    var h = $(this).val();
                    var f = "";
                    
                    for(var g = 0; g <= h.length; g++)
                    {
                        if(/^[a-zA-Z0-9\_]+$/.test(h.substring(g, g+1)))
                        {
                            f += h.substring(g, g+1);
                        }
                    }
                    
                    $(this).val(f);
                    $("#resetCode span").html(f);
                }
            );
            
            $(".wpbb_display_categories").live(
                "click",
                function()
                {
                    $("div.wpbb_categories").slideUp();
                    $("div.wpbb_display_categories").addClass("wpbb_display_open").removeClass("wpbb_display_close");
                    
                    if($(this).parent("th").find("div.wpbb_categories").is(":visible"))
                    {
                        $(this).parent("th").find("div.wpbb_categories").slideUp();
                        $(this).parent("th").find("div.wpbb_display_categories").addClass("wpbb_display_open").removeClass("wpbb_display_close");
                    }
                    else
                    {
                        $(this).parent("th").find("div.wpbb_categories").slideDown();
                        $(this).parent("th").find("div.wpbb_display_categories").removeClass("wpbb_display_open").addClass("wpbb_display_close");
                    }
                }
            );
            
            $(".wpbb_categories").each(
                function()
                {
                    $(this).find('input[type="checkbox"]').live(
                        "click",
                        function()
                        {
                            var f = $(this).parent("label").parent("div").find('input[type="checkbox"]:checked').length;
                            
                            $(this).parent("label").parent("div").find('input[name="forum_categories"]').val("");
                            
                            var g = 0;
                            
                            $(this).parent("label").parent("div").find('input[type="checkbox"]').each(
                                function()
                                {
                                    var h = $(this).parent("label").parent("div").find('input[name="forum_categories"]').val();
                                    
                                    if($(this).is(":checked"))
                                    {
                                        if(g > 0)
                                        {
                                            h += ", " + $(this).attr("value")
                                        }
                                        else
                                        {
                                            h += $(this).attr("value")
                                        }
                                        
                                        ++g
                                    }
                                    
                                    $(this).parent("label").parent("div").find('input[name="forum_categories"]').val(h)
                                }
                            )
                        }
                    )
                }
            );
            
            $(".wpbb_forums_submit").live(
                "click",
                function()
                {
                    var g = "{";
                    var f = 0;
                    
                    $(".wpbb_categories").each(
                        function()
                        {
                            if($(this).find('input[name="forum_categories"]').val().length>0)
                            {
                                f++;
                                
                                if(f == 1)
                                {
                                    g += '"set_' + f + '" : [{"categories":"' + $(this).find('input[name="forum_categories"]').val() + '", "forum" : "'
                                            + $(this).find('input[name="forum_id"]').val() + '"}]';
                                }
                                else
                                {
                                    g += ',"set_' + f + '" : [{"categories":"' + $(this).find('input[name="forum_categories"]').val() + '", "forum" : "'
                                            + $(this).find('input[name="forum_id"]').val() + '"}]'
                                }
                            }
                        }
                    );
                    
                    g += "}";
                    
                    $("#overlay").fadeIn();
                    
                    $.ajax(
                        {
                            type: "POST",
                            url: ajaxurl,
                            data: "action=save_forums&data=" + g,
                            success: function(h)
                            {
                                $("#overlay").fadeOut()
                            }
                        }
                    );
                    
                    return false
                }
            );
            
            $(".wpbb_authors_submit").live(
                "click",
                function()
                {
                    var g = "";
                    var f = 0;
                    $("#overlay").fadeIn();
                    
                    $(".user_id:checked").each(
                        function()
                        {
                            ++f;
                            if(f == 1)
                            {
                                g += $(this).attr("id");
                            }
                            else
                            {
                                g += "," + $(this).attr("id");
                            }
                        }
                    );
                    
                    $.ajax(
                        {
                            type: "POST",
                            url: ajaxurl,
                            data: "action=save_authors&data=" + g,
                            success: function(h)
                            {
                                $("#overlay").fadeOut();
                            }
                        }
                    )
                }
            );
            
            if($(".wp_phpbb_bridge_login").length > 0)
            {
                var b = $(".wp_phpbb_bridge_login").width();
                
                $("#wpbb_username").width(b).css("margin-bottom","15px");
                $("#wpbb_password").width(b).css("margin-bottom","15px");
                $("#wpbb_login").css("margin-top","15px").css("margin-bottom","15px");
            }
            
            if($("#wpbb_elements_width"))
            {
                var e = $("#wpbb_elements_width").val();
                e = parseInt(e,10);
                
                $("#wpbb_username").css({width:e+"px"});
                $("#wpbb_password").css({width:e+"px"});
                $("#blp").css({width:e+"px"})
            }
            
            $("#wpbb_status .handlediv").click(
                function()
                {
                    $(this).siblings(".inside").toggle()
                }
            );
            
            $("#wpbb_xtndit_info .handlediv").click(
                function()
                {
                    $(this).siblings(".inside").toggle()
                }
            );
            
            $("#wpbb_server_info .handlediv").click(
                function()
                {
                    $(this).siblings(".inside").toggle()
                }
            );
            
            $("#wpbb_plugin_info .handlediv").click(
                function()
                {
                    $(this).siblings(".inside").toggle()
                }
            );
            
            $("#wpbb_locale .handlediv").click(
                function()
                {
                    $(this).siblings(".inside").toggle()
                }
            );
            
            $("#wpbb_donators .handlediv").click(
                function()
                {
                    $(this).siblings(".inside").toggle()
                }
            )
        }
    )
};