{if $smarty.session.errors}
    {block name=error}
    {foreach $smarty.session.errors as $error}
        {if $error['type'] == 'error'}
            error - {$error['text']}
        {elseif $error['type'] == 'notify'}
            notify - {$error['text']}
        {/if}
    {/foreach}
    {/block}
{/if}