{if $smarty.session.errors}
    {block name=error}
    {foreach $smarty.session.errors as $error}
        {if $error['type'] == 'error'}
            <div class="alert alert-danger text-center" role="alert">
                {$error['text']}
            </div>
        {elseif $error['type'] == 'notify'}
            <div class="alert alert-warning  text-center" role="alert">
                {$error['text']}
            </div>
        {/if}
    {/foreach}
    {/block}
{/if}