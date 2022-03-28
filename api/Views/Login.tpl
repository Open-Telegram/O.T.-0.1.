{extends file='Api.tpl'}

{block name=body}
    <form method="post">
        <div class="row">
            <div class="col-sm-5">
                <div class="input-group mb-3">
                    <input name="login" type="text" class="form-control" placeholder="Login" aria-label="Username" aria-describedby="basic-addon1">
                    <span class="input-group-text" id="basic-addon1">Login</span>
                </div>
                <div class="input-group mb-3">
                    <input  name="password"  type="password" class="form-control" placeholder="Pasword" aria-label="Username" aria-describedby="basic-addon2">
                    <span class="input-group-text" id="basic-addon2">Password</span>
                </div>
                <button type="submit" class="btn btn-success">Success</button>
            </div>
        </div>
    </form>
{/block}