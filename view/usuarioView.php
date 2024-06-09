<?php

namespace App\view;

class UsuarioView
{

    public static function cadastrar($msg = null, $usuario = null, $Erro = null)
    {   	
        ?><h1 class = "container">Cadastre-se agora!</h1><?php
        if (isset($msg)):
            if (isset($Erro)): ?>
                <div class="erro"> 
            <?php else: ?>
                <div class="sucesso">
            <?php endif; ?>
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
                </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['ipw'])) : ?>
            <div class="erro">
                <?= 'A senha requer pelo menos 6 caracteres' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>

        <form action="?p=cadusr" method="post">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome" value="<?= (($usuario != null)? $usuario->__get('nome'): '') ?>" required>

            <label for="sobrenome">Sobrenome: </label>
            <input type="text" name="sobrenome" id="sobrenome" value="<?= (($usuario != null)? $usuario->__get('sobrenome'): '') ?>" required>

            <label for="data_nascimento">Data de Nascimento: </label>
            <input type="date" name="data_nascimento" id="data_nascimento" value="<?= (($usuario != null)? $usuario->__get('data_nascimento'): '') ?>" required>

            <label for="cpf">CPF: </label>
            <input type="text" name="cpf" id="cpf" maxlength="11" value="<?= (($usuario != null)? $usuario->__get('cpf'): '') ?>" required>

            <label for="ddd">DDD: </label>
            <input type="text" name="ddd" id="ddd" maxlength="2" value="<?= (($usuario != null)? $usuario->__get('ddd'): '') ?>" required>

            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone" maxlength="10" value="<?= (($usuario != null)? $usuario->__get('telefone'): '') ?>" required>

            <label for="username">Usuario: </label>
            <input type="text" name="username" id="username" maxlength="30" value="<?= (($usuario != null)? $usuario->__get('username'): '') ?>" required>

            <label for="pass">Senha: </label>
            <input type="password" name="pass" id="pass" maxlength="30"  required>

            <label for="confirmar">Confirmar senha: </label>
            <input type="password" name="confirmar" id="confirmar" maxlength="30"  required>


            <button type="submit">Salvar</button>
        </form>
        <?php
    }

    public static function recuperar($msg = null, $Erro = null)
    {   	
        ?><h1 class = "container">Verifique seus dados</h1><?php
        if (isset($msg)):
            if (isset($Erro)): ?>
                <div class="erro"> 
            <?php else: ?>
                <div class="sucesso">
            <?php endif; ?>
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
                </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['ipw'])) : ?>
            <div class="erro">
                <?= 'A senha requer pelo menos 6 caracteres' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>

        <form action="?p=recusr" method="post">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="cpf">CPF: </label>
            <input type="text" name="cpf" id="cpf" maxlength="11" required>

            <label for="data_nascimento">Data de Nascimento: </label>
            <input type="date" name="data_nascimento" id="data_nascimento" required>

            <button type="submit">Enviar</button>
        </form>
        <?php
    }

    public static function profile($usuario = null, $msg = null)
    {
        ?><h1 class = "container">Informações da Conta</h1><?php
        if (isset($msg)) : ?>
            <div class="sucesso">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>

        <form action="?p=profile" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $usuario ? $usuario->__get('id') : '' ?>">

            <label for="nome">Nome: </label>
            <input required type="text" name="nome" id="nome" value="<?= $usuario ? $usuario->__get('nome') : '' ?>">

            <label for="sobrenome">Sobrenome: </label>
            <input required type="text" name="sobrenome" id="sobrenome" value="<?= $usuario ? $usuario->__get('sobrenome') : '' ?>">

            <label for="data_nascimento">Data de Nascimento: </label>
            <input required type="date" name="data_nascimento" id="data_nascimento" value="<?= $usuario ? $usuario->__get('data_nascimento') : '' ?>">

            <label for="cpf">CPF: </label>
            <input required type="text" name="cpf" id="cpf" maxlength="11" value="<?= $usuario ? $usuario->__get('cpf') : '' ?>">

            <label for="ddd">DDD: </label>
            <input required type="text" name="ddd" id="ddd" maxlength="2" value="<?= $usuario ? $usuario->__get('ddd') : '' ?>">

            <label for="telefone">Telefone: </label>
            <input required type="text" name="telefone" id="telefone" maxlength="10" value="<?= $usuario ? $usuario->__get('telefone') : '' ?>">

            <label for="username">Usuário: </label>
            <input required disabled type="text" name="username" id="username" maxlength="30" value="<?= $usuario ? $usuario->__get('username') : '' ?>">

            <label for="pass">Senha: </label>
            <input required type="password" name="pass" id="pass" maxlength="30" placeholder="Insira sua senha">

            <button type="submit">Salvar</button>
        </form>
        <?php
    }

    //MASTER
    public static function listar($usuarios, $msg = null)
    {
        ?><h1 class = "container">Usuarios Cadastrados</h1><?php
        if (isset($msg)) : ?>
            <div class="sucesso">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        <table>
            <tr>
                <th>Id</th>
                <th>Group</th>
                <th>Nome</th>
                <th>Sobrenome</th>
                <th>Data de Nascimento</th>
                <th>CPF</th>
                <th>DDD</th>
                <th>Telefone</th>
                <th>Usuario</th>
                <th>Alterar</th>
                <th>Excluir</th>
            </tr>
            <?php foreach ($usuarios as $usuario) : ?>
                <tr>
                    <td><?= $usuario->__get("id") ?></td>
                    <td><?= $usuario->__get("groupID") ?></td>
                    <td><?= $usuario->__get("nome") ?></td>
                    <td><?= $usuario->__get("sobrenome") ?></td>
                    <td><?= $usuario->__get("data_nascimento") ?></td>
                    <td><?= $usuario->__get("cpf") ?></td>
                    <td><?= $usuario->__get("ddd") ?></td>
                    <td><?= $usuario->__get("telefone") ?></td>
                    <td><?= $usuario->__get("username") ?></td>
                    <td><a href="?p=altusr&alt=<?= $usuario->__get("id") ?>">Alterar</a></td>
                    <td><a href="?p=delusr&del=<?= $usuario->__get("id") ?>">Excluir</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }

    public static function alterar($msg = null, $usuario = null, $Erro = null)
    {
        ?><h1 class = "container">Alterar usuario</h1><?php
        if (isset($msg)):
            if (isset($Erro)): ?>
                <div class="erro"> 
            <?php else: ?>
                <div class="sucesso">
            <?php endif; ?>
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
                </div>
        <?php endif;
        if (isset($_GET['epw'])) : ?>
            <div class="erro">
                <?= 'Senha Incorreta.' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>

        <form action="?p=altusr" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $usuario ? $usuario->__get('id') : '' ?>">

            <label for="nome">Group: </label>
            <input required type="text" name="groupID" id="groupID" value="<?= $usuario ? $usuario->__get('groupID') : '' ?>">

            <label for="nome">Nome: </label>
            <input required type="text" name="nome" id="nome" value="<?= $usuario ? $usuario->__get('nome') : '' ?>">

            <label for="sobrenome">Sobrenome: </label>
            <input required type="text" name="sobrenome" id="sobrenome" value="<?= $usuario ? $usuario->__get('sobrenome') : '' ?>">

            <label for="ddd">DDD: </label>
            <input required type="text" name="ddd" id="ddd" maxlength="2" value="<?= $usuario ? $usuario->__get('ddd') : '' ?>">

            <label for="telefone">Telefone: </label>
            <input required type="text" name="telefone" id="telefone" maxlength="10" value="<?= $usuario ? $usuario->__get('telefone') : '' ?>">

            <label for="username">Usuário: </label>
            <input required type="text" name="username" id="username" maxlength="30" value="<?= $usuario ? $usuario->__get('username') : '' ?>">

            <button type="submit">Salvar</button>
        </form>
<?php
    }

    public static function alterarSenha($msg = null, $usuario, $Erro = null)
    {
        ?><h1 class = "container">Digite e confirme sua nova senha</h1><?php
        if (isset($msg)):
            if (isset($Erro)): ?>
                <div class="erro"> 
            <?php else: ?>
                <div class="sucesso">
            <?php endif; ?>
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
                </div>
        <?php endif;?>

        <form action="?p=recpw" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $usuario ? $usuario->__get('id') : '' ?>">

            <label for="pass">Senha: </label>
            <input required type="password" name="pass" id="pass" maxlength="30">

            <label for="confirmar">Confirmar senha: </label>
            <input required type="password" name="confirmar" id="confirmar" maxlength="30">

            <button type="submit">Enviar</button>
        </form>
<?php
    }
}
