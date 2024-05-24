<?php
namespace App\view;

use App\dal\UsuarioDao;

class MasterView{

    public static function listar($usuarios, $msg = null){
        if (isset($msg)): ?>
            <div class="sucesso">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>        
            </div>
            <?php endif; 
            if (isset($_GET['epw'])): ?>
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
                <th>Senha</th>
                <th>Alterar</th>
                <th>Excluir</th>
            </tr>
            <?php foreach($usuarios as $usuario): ?>
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
                <td><?= substr($usuario->__get("pass"), 0, 30); ?></td>
                <td><a href="?p=altusr&alt=<?= $usuario->__get("id") ?>">Alterar</a></td>
                <td><a href="?p=delusr&del=<?= $usuario->__get("id") ?>">Excluir</a></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }

    public static function alterar($usuario = null, $msg = null) {
        if (isset($msg)): ?>
            <div class="sucesso">
                <?= $msg ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; 
        if (isset($_GET['epw'])): ?>
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
    
            <label for="data_nascimento">Data de Nascimento: </label>
            <input required type="date" name="data_nascimento" id="data_nascimento" value="<?= $usuario ? $usuario->__get('data_nascimento') : '' ?>">
    
            <label for="cpf">CPF: </label>
            <input required type="text" name="cpf" id="cpf" maxlength="11" value="<?= $usuario ? $usuario->__get('cpf') : '' ?>">
    
            <label for="ddd">DDD: </label>
            <input required type="text" name="ddd" id="ddd" maxlength="2" value="<?= $usuario ? $usuario->__get('ddd') : '' ?>">
    
            <label for="telefone">Telefone: </label>
            <input required type="text" name="telefone" id="telefone" maxlength="10" value="<?= $usuario ? $usuario->__get('telefone') : '' ?>">
    
            <label for="username">Usuário: </label>
            <input required type="text" name="username" id="username" maxlength="30" value="<?= $usuario ? $usuario->__get('username') : '' ?>">
    
            <?php if (isset($_SESSION['username']) && UsuarioDao::buscarPorUsername($_SESSION['username'])->__get('groupID') == 1): ?>
                <label for="pass">Senha: </label>
                <input required type="password" name="pass" id="pass" maxlength="30" placeholder="Insira sua senha">
            <?php endif; ?>
    
            <button type="submit">Salvar</button>
        </form>
        <?php
    }
}