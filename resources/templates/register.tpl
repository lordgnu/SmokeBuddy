<div data-role="page" id="register">
    {include file='global/jqm.header.tpl'}
    <div data-role="content">
        <div data-role="collapsible-set">
	        <div data-role="collapsible" data-theme="a" data-content-theme="d" data-collapsed="{if $error === false}false{else}true{/if}">
		        <h3>Already Registered?</h3>
		        
		        {if $loginError !== false}
                <div class="ui-body ui-body-e">
                    <p>{$loginError}</p>
                </div>
                {/if}
		        
		        <form method="post" action="/login">
			        <!-- Drop Down Here -->
			        <div data-role="fieldcontain">
	                    <label for="login-name" class="select">Who Are You?:</label>
	                    <select name="login-name" id="login-name" data-native-menu="true" data-mini="true">
	                        {foreach $DATA.users as $user}
	                        <option>{$user.name}</option>
	                        {/foreach}
	                    </select>
	                </div>
	                
			        <!-- Pin Here -->
			        <div data-role="fieldcontain">
	                    <label for="login-pin">4-Digit PIN #:</label>
	                    <input type="text" name="login-pin" id="login-pin" data-mini="true" maxlength="4" />
	                </div>
	                
	                <button type="submit" data-theme="a" data-mini="true">Submit</button>
		        </form>
	        </div>
	        <div data-role="collapsible" data-theme="a" data-content-theme="d" data-collapsed="{if $error === false}true{else}false{/if}">
	            <h3>Register</h3>
	            
	            {if $error !== false}
	            <div class="ui-body ui-body-e">
	                <p>{$error}</p>
	            </div>
	            {/if}
	            
	            <form method="post" action="/register/submit">
	                {*
	                <!-- Organization -->
	                <div data-role="fieldcontain">
	                    <label for="orgnanization" class="select">Organization:</label>
	                    <select name="organization" id="orangization" data-native-menu="false" data-mini="true">
	                        <option>Charter Communications</option>
	                        <option>Crouching Llama</option>
	                    </select>
	                </div>
	                *}
	                <!-- Name -->
	                <div data-role="fieldcontain">
	                    <label for="name">Full Name:</label>
	                    <input type="text" name="name" id="name" data-mini="true" value="{$smarty.post.name}" />
	                </div>
	                
	                <!-- Pin Number -->
	                <div data-role="fieldcontain">
	                    <label for="pin">4-Digit PIN #:</label>
	                    <input type="text" name="pin" id="pin" data-mini="true" maxlength="4" value="{$smarty.post.pin}" />
	                </div>
	                
	                <!-- Smoke Timer -->
	                <div data-role="fieldcontain">
	                    <label for="timer">Smoke Timer (Minutes):</label>
	                    <input type="range" name="timer" id="timer" value="{$smarty.post.timer|default:'10'}" min="5" max="30" data-highlight="true" data-mini="true" />
	                </div>
	                
	                <!-- Submit -->
	                <button type="submit" data-theme="a" data-mini="true">Submit</button>
	                
	            </form>
	        </div>
        </div>
    </div>
    {include file='global/jqm.footer.tpl'}
</div>