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
	
		<form id="create-account-form" class="myoos-form" action="{html_get_link connection=SSL}" method="post">
			<input type="hidden" name="{$oos_session_name}" value="{$oos_session_id}">
			<input type="hidden" name="formid" value="{$formid}">
			<input type="hidden" name="action" value="process">
			<input type="hidden" name="content" value="{$contents.create_account}">		

{if $smarty.const.ACCOUNT_GENDER eq 'true'}
			<fieldset>
				<div class="row">
					<section>
						<div class="inline-group">
							<label class="radio">
								<input type="radio" name="gender" value="m">
								<i></i>{$lang.male}</label>
							<label class="radio">
								<input type="radio" name="gender" value="f">
								<i></i>{$lang.female}</label>
						</div>
					</section>
			</fieldset>
{/if}			
			<fieldset>
								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="fname" placeholder="First name">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-user"></i>
											<input type="text" name="lname" placeholder="Last name">
										</label>
									</section>
								</div>

								<div class="row">
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-envelope-o"></i>
											<input type="email" name="email_address" value="{$email_address}" placeholder="{$lang.entry_email_address}">
										</label>
									</section>
									<section class="col col-6">
										<label class="input"> <i class="icon-prepend fa fa-phone"></i>
											<input type="tel" name="telephone" value="{$telephone}" placeholder="{$lang.entry_telephone_number}" data-mask="(999) 999-9999">
										</label>
									</section>
								</div>
			</fieldset>

			
          </tr>

          <tr>
            <td class="main">&nbsp;{$lang.entry_post_code}</td>
            <td class="main">&nbsp;<input type="text" name="postcode" value="{$account.entry_postcode}">&nbsp;&nbsp;<small>{$lang.entry_info_text}</small></td>
          </tr>
          <tr>
            <td class="main">&nbsp;{$lang.entry_city}</td>
            <td class="main">&nbsp;<input type="text" name="city" value="{$account.entry_city}">&nbsp;&nbsp;<small>{$lang.entry_info_text}</small></td>
          </tr>
{if $smarty.const.ACCOUNT_STATE eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_state}</td>
            <td class="main">&nbsp;<input type="text" name="state" value="{oos_get_zone_name country_id=$account.entry_country_id zone_id=$account.entry_zone_id default_zone=$account.entry_state}">&nbsp;&nbsp;<small>{$lang.entry_info_text}</font></small></td>
          </tr>
{/if}
          <tr>
            <td class="main">&nbsp;{$lang.entry_country}</td>
            <td class="main">&nbsp;{oos_get_country_list name=country selected=$account.entry_country_id}&nbsp;&nbsp;<small>{$lang.entry_info_text}</small></td>
          </tr>
        </table></td>			
			
			
			<fieldset>
								<div class="row">
									<section class="col col-5">
										<label class="select">
										

											<select name="country">
												<option value="0" selected="" disabled="">Country</option>
												<option value="244">Aaland Islands</option>
												<option value="1">Afghanistan</option>
												<option value="2">Albania</option>
												<option value="3">Algeria</option>
												<option value="4">American Samoa</option>
												<option value="5">Andorra</option>
												<option value="6">Angola</option>
												<option value="7">Anguilla</option>
												<option value="8">Antarctica</option>
												<option value="9">Antigua and Barbuda</option>
												<option value="10">Argentina</option>
												<option value="11">Armenia</option>
												<option value="12">Aruba</option>
												<option value="13">Australia</option>
												<option value="14">Austria</option>
												<option value="15">Azerbaijan</option>
												<option value="16">Bahamas</option>
												<option value="17">Bahrain</option>
												<option value="18">Bangladesh</option>
												<option value="19">Barbados</option>
												<option value="20">Belarus</option>
												<option value="21">Belgium</option>
												<option value="22">Belize</option>
												<option value="23">Benin</option>
												<option value="24">Bermuda</option>
												<option value="25">Bhutan</option>
												<option value="26">Bolivia</option>
												<option value="245">Bonaire, Sint Eustatius and Saba</option>
												<option value="27">Bosnia and Herzegovina</option>
												<option value="28">Botswana</option>
												<option value="29">Bouvet Island</option>
												<option value="30">Brazil</option>
												<option value="31">British Indian Ocean Territory</option>
												<option value="32">Brunei Darussalam</option>
												<option value="33">Bulgaria</option>
												<option value="34">Burkina Faso</option>
												<option value="35">Burundi</option>
												<option value="36">Cambodia</option>
												<option value="37">Cameroon</option>
												<option value="38">Canada</option>
												<option value="251">Canary Islands</option>
												<option value="39">Cape Verde</option>
												<option value="40">Cayman Islands</option>
												<option value="41">Central African Republic</option>
												<option value="42">Chad</option>
												<option value="43">Chile</option>
												<option value="44">China</option>
												<option value="45">Christmas Island</option>
												<option value="46">Cocos (Keeling) Islands</option>
												<option value="47">Colombia</option>
												<option value="48">Comoros</option>
												<option value="49">Congo</option>
												<option value="50">Cook Islands</option>
												<option value="51">Costa Rica</option>
												<option value="52">Cote D'Ivoire</option>
												<option value="53">Croatia</option>
												<option value="54">Cuba</option>
												<option value="246">Curacao</option>
												<option value="55">Cyprus</option>
												<option value="56">Czech Republic</option>
												<option value="237">Democratic Republic of Congo</option>
												<option value="57">Denmark</option>
												<option value="58">Djibouti</option>
												<option value="59">Dominica</option>
												<option value="60">Dominican Republic</option>
												<option value="61">East Timor</option>
												<option value="62">Ecuador</option>
												<option value="63">Egypt</option>
												<option value="64">El Salvador</option>
												<option value="65">Equatorial Guinea</option>
												<option value="66">Eritrea</option>
												<option value="67">Estonia</option>
												<option value="68">Ethiopia</option>
												<option value="69">Falkland Islands (Malvinas)</option>
												<option value="70">Faroe Islands</option>
												<option value="71">Fiji</option>
												<option value="72">Finland</option>
												<option value="74">France, skypolitan</option>
												<option value="75">French Guiana</option>
												<option value="76">French Polynesia</option>
												<option value="77">French Southern Territories</option>
												<option value="126">FYROM</option>
												<option value="78">Gabon</option>
												<option value="79">Gambia</option>
												<option value="80">Georgia</option>
												<option value="81">Germany</option>
												<option value="82">Ghana</option>
												<option value="83">Gibraltar</option>
												<option value="84">Greece</option>
												<option value="85">Greenland</option>
												<option value="86">Grenada</option>
												<option value="87">Guadeloupe</option>
												<option value="88">Guam</option>
												<option value="89">Guatemala</option>
												<option value="241">Guernsey</option>
												<option value="90">Guinea</option>
												<option value="91">Guinea-Bissau</option>
												<option value="92">Guyana</option>
												<option value="93">Haiti</option>
												<option value="94">Heard and Mc Donald Islands</option>
												<option value="95">Honduras</option>
												<option value="96">Hong Kong</option>
												<option value="97">Hungary</option>
												<option value="98">Iceland</option>
												<option value="99">India</option>
												<option value="100">Indonesia</option>
												<option value="101">Iran (Islamic Republic of)</option>
												<option value="102">Iraq</option>
												<option value="103">Ireland</option>
												<option value="104">Israel</option>
												<option value="105">Italy</option>
												<option value="106">Jamaica</option>
												<option value="107">Japan</option>
												<option value="240">Jersey</option>
												<option value="108">Jordan</option>
												<option value="109">Kazakhstan</option>
												<option value="110">Kenya</option>
												<option value="111">Kiribati</option>
												<option value="113">Korea, Republic of</option>
												<option value="114">Kuwait</option>
												<option value="115">Kyrgyzstan</option>
												<option value="116">Lao People's Democratic Republic</option>
												<option value="117">Latvia</option>
												<option value="118">Lebanon</option>
												<option value="119">Lesotho</option>
												<option value="120">Liberia</option>
												<option value="121">Libyan Arab Jamahiriya</option>
												<option value="122">Liechtenstein</option>
												<option value="123">Lithuania</option>
												<option value="124">Luxembourg</option>
												<option value="125">Macau</option>
												<option value="127">Madagascar</option>
												<option value="128">Malawi</option>
												<option value="129">Malaysia</option>
												<option value="130">Maldives</option>
												<option value="131">Mali</option>
												<option value="132">Malta</option>
												<option value="133">Marshall Islands</option>
												<option value="134">Martinique</option>
												<option value="135">Mauritania</option>
												<option value="136">Mauritius</option>
												<option value="137">Mayotte</option>
												<option value="138">Mexico</option>
												<option value="139">Micronesia, Federated States of</option>
												<option value="140">Moldova, Republic of</option>
												<option value="141">Monaco</option>
												<option value="142">Mongolia</option>
												<option value="242">Montenegro</option>
												<option value="143">Montserrat</option>
												<option value="144">Morocco</option>
												<option value="145">Mozambique</option>
												<option value="146">Myanmar</option>
												<option value="147">Namibia</option>
												<option value="148">Nauru</option>
												<option value="149">Nepal</option>
												<option value="150">Netherlands</option>
												<option value="151">Netherlands Antilles</option>
												<option value="152">New Caledonia</option>
												<option value="153">New Zealand</option>
												<option value="154">Nicaragua</option>
												<option value="155">Niger</option>
												<option value="156">Nigeria</option>
												<option value="157">Niue</option>
												<option value="158">Norfolk Island</option>
												<option value="112">North Korea</option>
												<option value="159">Northern Mariana Islands</option>
												<option value="160">Norway</option>
												<option value="161">Oman</option>
												<option value="162">Pakistan</option>
												<option value="163">Palau</option>
												<option value="247">Palestinian Territory, Occupied</option>
												<option value="164">Panama</option>
												<option value="165">Papua New Guinea</option>
												<option value="166">Paraguay</option>
												<option value="167">Peru</option>
												<option value="168">Philippines</option>
												<option value="169">Pitcairn</option>
												<option value="170">Poland</option>
												<option value="171">Portugal</option>
												<option value="172">Puerto Rico</option>
												<option value="173">Qatar</option>
												<option value="174">Reunion</option>
												<option value="175">Romania</option>
												<option value="176">Russian Federation</option>
												<option value="177">Rwanda</option>
												<option value="178">Saint Kitts and Nevis</option>
												<option value="179">Saint Lucia</option>
												<option value="180">Saint Vincent and the Grenadines</option>
												<option value="181">Samoa</option>
												<option value="182">San Marino</option>
												<option value="183">Sao Tome and Principe</option>
												<option value="184">Saudi Arabia</option>
												<option value="185">Senegal</option>
												<option value="243">Serbia</option>
												<option value="186">Seychelles</option>
												<option value="187">Sierra Leone</option>
												<option value="188">Singapore</option>
												<option value="189">Slovak Republic</option>
												<option value="190">Slovenia</option>
												<option value="191">Solomon Islands</option>
												<option value="192">Somalia</option>
												<option value="193">South Africa</option>
												<option value="194">South Georgia &amp; South Sandwich Islands</option>
												<option value="248">South Sudan</option>
												<option value="195">Spain</option>
												<option value="196">Sri Lanka</option>
												<option value="249">St. Barthelemy</option>
												<option value="197">St. Helena</option>
												<option value="250">St. Martin (French part)</option>
												<option value="198">St. Pierre and Miquelon</option>
												<option value="199">Sudan</option>
												<option value="200">Suriname</option>
												<option value="201">Svalbard and Jan Mayen Islands</option>
												<option value="202">Swaziland</option>
												<option value="203">Sweden</option>
												<option value="204">Switzerland</option>
												<option value="205">Syrian Arab Republic</option>
												<option value="206">Taiwan</option>
												<option value="207">Tajikistan</option>
												<option value="208">Tanzania, United Republic of</option>
												<option value="209">Thailand</option>
												<option value="210">Togo</option>
												<option value="211">Tokelau</option>
												<option value="212">Tonga</option>
												<option value="213">Trinidad and Tobago</option>
												<option value="214">Tunisia</option>
												<option value="215">Turkey</option>
												<option value="216">Turkmenistan</option>
												<option value="217">Turks and Caicos Islands</option>
												<option value="218">Tuvalu</option>
												<option value="219">Uganda</option>
												<option value="220">Ukraine</option>
												<option value="221">United Arab Emirates</option>
												<option value="222">United Kingdom</option>
												<option value="223">United States</option>
												<option value="224">United States Minor Outlying Islands</option>
												<option value="225">Uruguay</option>
												<option value="226">Uzbekistan</option>
												<option value="227">Vanuatu</option>
												<option value="228">Vatican City State (Holy See)</option>
												<option value="229">Venezuela</option>
												<option value="230">Viet Nam</option>
												<option value="231">Virgin Islands (British)</option>
												<option value="232">Virgin Islands (U.S.)</option>
												<option value="233">Wallis and Futuna Islands</option>
												<option value="234">Western Sahara</option>
												<option value="235">Yemen</option>
												<option value="238">Zambia</option>
												<option value="239">Zimbabwe</option>
											</select> <i></i> </label>
										</section>

										<section class="col col-4">
										<label class="input">
											<input type="text" name="city" placeholder="City">
										</label>
									</section>

									<section class="col col-3">
										<label class="input">
											<input type="text" name="code" placeholder="Post code">
										</label>
									</section>
								</div>

								<section>
									<label for="address2" class="input">
										<input type="text" name="address2" id="address2" placeholder="Address">
									</label>
								</section>

								<section>
									<label class="textarea"> 										
										<textarea rows="3" name="info" placeholder="Additional info"></textarea> 
									</label>
								</section>
			</fieldset>

			<fieldset>
								<section>
									<div class="inline-group">
										<label class="radio">
											<input type="radio" name="radio-inline" checked="">
											<i></i>Visa</label>
										<label class="radio">
											<input type="radio" name="radio-inline">
											<i></i>MasterCard</label>
										<label class="radio">
											<input type="radio" name="radio-inline">
											<i></i>American Express</label>
									</div>
								</section>

								<section>
									<label class="input">
										<input type="text" name="name" placeholder="Name on card">
									</label>
								</section>

								<div class="row">
									<section class="col col-10">
										<label class="input">
											<input type="text" name="card" placeholder="Card number" data-mask="9999-9999-9999-9999">
										</label>
									</section>
									<section class="col col-2">
										<label class="input">
											<input type="text" name="cvv" placeholder="CVV2" data-mask="999">
										</label>
									</section>
								</div>

								<div class="row">
									<label class="label col col-4">Expiration date</label>
									<section class="col col-5">
										<label class="select">
											<select name="month">
												<option value="0" selected="" disabled="">Month</option>
												<option value="1">January</option>
												<option value="1">February</option>
												<option value="3">March</option>
												<option value="4">April</option>
												<option value="5">May</option>
												<option value="6">June</option>
												<option value="7">July</option>
												<option value="8">August</option>
												<option value="9">September</option>
												<option value="10">October</option>
												<option value="11">November</option>
												<option value="12">December</option>
											</select> <i></i> </label>
									</section>
									<section class="col col-3">
										<label class="input">
											<input type="text" name="year" placeholder="Year" data-mask="2099">
										</label>
									</section>
								</div>
							</fieldset>

							<footer>
								<button type="submit" class="btn btn-primary">
									Validate Form
								</button>
							</footer>
		</form>
	
    </div>
    </section>
	</div> <!-- / .wrapper -->




     <table border="0" width="100%" cellspacing="0" cellpadding="2">
         <tr>
           <td class="formAreaTitle">{$lang.category_personal}</td>
         </tr>
         <tr>
           <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
            <tr>
              <td class="main"><table border="0" cellspacing="0" cellpadding="2">
{if $smarty.const.ACCOUNT_GENDER eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_gender}</td>
            <td class="main">&nbsp;
  {if $gender_error eq 'true'}
      <input type="radio" name="gender" value="m" CHECKED>&nbsp;&nbsp;{$lang.male}&nbsp;&nbsp;<input type="radio" name="gender" value="f">&nbsp;&nbsp;{$lang.female}&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_info_text}</font></small></td>
  {else}
    {if $gender eq 'm' }
      {$lang.male}
    {else}
      {$lang.female}
    {/if}
      <input type=hidden name="gender" value="{$gender}">
  {/if}
            </td>
          </tr>
{/if}
          <tr>
            <td class="main">&nbsp;{$lang.entry_first_name}</td>
            <td class="main">&nbsp;
  {if $firstname_error eq 'true'}
     <input type="text" name="firstname" value="{$firstname}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_first_name_error}</font></small>
  {else}
     {$firstname}<input type=hidden name="firstname" value="{$firstname}">
  {/if}
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;{$lang.entry_last_name}</td>
            <td class="main">&nbsp;
  {if $lastname_error eq 'true'}
     <input type="text" name="lastname" value="{$lastname}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_last_name_error}</font></small>
  {else}
     {$lastname}<input type=hidden name="lastname" value="{$lastname}">
  {/if}
            </td>
          </tr>
{if $smarty.const.ACCOUNT_DOB eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_date_of_birth}</td>
            <td class="main">&nbsp;
  {if $date_of_birth_error eq 'true'}
     <input type="text" name="dob" value="{$dob}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_date_of_birth_text}</font></small>
  {else}
     {$dob}<input type=hidden name="dob" value="{$dob}">
  {/if}
            </td>
          </tr>
{/if}
{if $smarty.const.ACCOUNT_NUMBER eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_number}</td>
            <td class="main">&nbsp;{$number}<input type=hidden name="number" value="{$number}"></td>
          </tr>
{/if}
         <tr>
            <td class="main">&nbsp;{$lang.entry_email_address}</td>
            <td class="main">&nbsp;
  {if $email_address_error eq 'true'}
      <input type="text" name="email_address" value="{$email_address}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_email_address_error}</font></small>
  {elseif $email_address_check_error eq 'true'}
      <input type="text" name="email_address" value="{$email_address}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_email_address_check_error}</font></small>
  {elseif $email_address_exists eq 'true'}
      <input type="text" name="email_address" value="{$email_address}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_email_address_error_exists}</font></small>
  {else}
      {$email_address}<input type=hidden name="email_address" value="{$email_address}">
  {/if}
        </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
{if $smarty.const.ACCOUNT_COMPANY eq 'true'}
  <tr>
    <td class="formAreaTitle"><br>{$lang.category_company}</td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;{$lang.entry_company}</td>
            <td class="main">&nbsp;{$company}<input type=hidden name="company" value="{$company}"></td>
          </tr>

  {if $smarty.const.ACCOUNT_VAT_ID eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_vat_id}</td>
            <td class="main">&nbsp;
    {if $vatid_check_error eq 'true'}
        <input type="text" name="vat_id" value="{$vat_id}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_vat_id_error}</font></small>
    {else}
        {$vat_id}<input type=hidden name="vat_id" value="{$vat_id}">
    {/if}
             </td>
          </tr>
  {/if}
        </table></td>
      </tr>
    </table></td>
  </tr>
{/if}
  <tr>
    <td class="formAreaTitle"><br>{$lang.category_address}</td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;{$lang.entry_street_address}</td>
            <td class="main">&nbsp;
  {if $street_address_error eq 'true'}
     <input type="text" name="street_address" value="{$street_address}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_street_address_error}</font></small>
  {else}
     {$street_address}<input type=hidden name="street_address" value="{$street_address}">
  {/if}
            </td>
          </tr>
{if $smarty.const.ACCOUNT_SUBURB eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_suburb}</td>
            <td class="main">&nbsp;{$suburb}<input type=hidden name="suburb" value="{$suburb}"></td>
          </tr>
{/if}
          <tr>
            <td class="main">&nbsp;{$lang.entry_post_code}</td>
            <td class="main">&nbsp;
  {if $post_code_error eq 'true'}
     <input type="text" name="postcode" value="{$postcode}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_post_code_error}</font></small>
  {else}
     {$postcode}<input type=hidden name="postcode" value="{$postcode}">
  {/if}
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;{$lang.entry_city}</td>
            <td class="main">&nbsp;
  {if $city_error eq 'true'}
     <input type="text" name="city" value="{$city}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_city_error}</font></small>
  {else}
     {$city}<input type=hidden name="city" value="{$city}">
  {/if}
            </td>
          </tr>
{if $smarty.const.ACCOUNT_STATE eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_state}</td>
            <td class="main">&nbsp;
  {if $state_error eq 'true'}
      {if $state_has_zones eq 'true'}
          <select name=state>{html_options values=$zones_values output=$zones_names}</select>&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_info_text}</font></small>
      {else}
          <input type="text" name="state" value="">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_state_error}</font></small>
      {/if}
  {else}
     {$state} <input type=hidden name="zone_id" value="{$zone_id}"><input type=hidden name="state" value="{$state}">
  {/if}
            </td>
          </tr>
{/if}
          <tr>
            <td class="main">&nbsp;{$lang.entry_country}</td>
            <td class="main">&nbsp;
  {if $country_error eq 'true'}
     {oos_get_country_list name=country}&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_country_error}</font></small>
  {else}
     {$country_name}<input type=hidden name="country" value="{$country}">
  {/if}
            </td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br>{$lang.category_contact}</td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;{$lang.entry_telephone_number}</td>
            <td class="main">&nbsp;
  {if $telephone_error eq 'true'}
     <input type="text" name="telephone" value="{$telephone}">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_telephone_number_error}</font></small>
  {else}
     {$telephone}<input type=hidden name="telephone" value="{$telephone}">
  {/if}
            </td>
          </tr>
          <tr>
            <td class="main">&nbsp;{$lang.entry_fax_number}</td>
            <td class="main">&nbsp;{$fax}<input type=hidden name="fax" value="{$fax}"></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td class="formAreaTitle"><br>{$lang.category_options}</td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;{$lang.entry_newsletter}</td>
            <td class="main">&nbsp;{$news}<input type=hidden name="newsletter" value="{$newsletter}"></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
{if $show_password eq 'true'}
  <tr>
    <td class="formAreaTitle"><br>{$lang.category_password}</td>
  </tr>
  <tr>
    <td class="main"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="formArea">
      <tr>
        <td class="main"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main">&nbsp;{$lang.entry_password}</td>
            <td class="main">&nbsp;
  {if $password_error eq 'true'}
     <input type="password" name="password" maxlength="40">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_password_error}</font></small>
  {else}
     {$password_hidden}<input type=hidden name="password" value="{$password}"><input type=hidden name="confirmation" value="{$confirmation}">
  {/if}
            </td>
          </tr>
  {if $password_error eq 'true'}
          <tr>
            <td class="main">&nbsp;{$lang.entry_password_confirmation}</td>
            <td class="main">&nbsp;<input type="password" name="confirmation" maxlength="40">&nbsp;&nbsp;<small><font color="#FF0000">{$lang.entry_password_confirmation}</font></small></td>
          </tr>
  {/if}
        </table></td>
      </tr>
    </table></td>
  </tr>
{/if}
</table>


        </td>
      </tr>
      <tr>
        <td height="10"></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right">{html_image_submit image="continue.gif" alt=$lang.button_continue}</td>
          </tr>
        </table></td>
      </tr>
    </table></form>



<script src="js/plugin/jquery-form/jquery-form.min.js"></script>

{literal}
	{$javascript}
{/literal}
	
{include file="myoos/system/_footer.tpl"}