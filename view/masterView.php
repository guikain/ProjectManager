<?php
namespace App\view;

use App\dal\UsuarioDao;

class MasterView{

    public static function listarusuario($usuarios, $msg = null){
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

    public static function alterarUsuario($usuario = null, $msg = null) {
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
    
            <button type="submit">Salvar</button>
        </form>
        <?php
    }

    public static function cadastrarProjeto($msg = null){
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
        <?php if (isset($_GET['ipw'])): ?>
            <div class="erro">
                <?= 'A senha requer pelo menos 8 caracteres' ?>
                <span class="close" onclick="this.parentElement.style.display='none'">&times;</span>
            </div>
        <?php endif; ?>
        
            <form action="?p=cadusr" method="post">

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

            <label for="nome">Nome: </label>
            <input type="text" name="nome" id="nome" required>

            <label for="sobrenome">Sobrenome: </label>
            <input type="text" name="sobrenome" id="sobrenome" required>

            <label for="data_nascimento">Data de Nascimento: </label>
            <input type="date" name="data_nascimento" id="data_nascimento" required>

            <label for="cpf">CPF: </label>
            <input type="text" name="cpf" id="cpf" maxlength="11" required> 

            <label for="ddd">DDD: </label>
            <input type="text" name="ddd" id="ddd" maxlength="2" required>

            <label for="telefone">Telefone: </label>
            <input type="text" name="telefone" id="telefone" maxlength="10" required>

            <label for="username">Usuario: </label>
            <input type="text" name="username" id="username" maxlength="30" required>

            <label for="pass">Senha: </label>
            <input type="password" name="pass" id="pass" maxlength="30" required>


            <button type="submit">Salvar</button>
            </form>
        <?php
    }

    public static function alterarProjeto($projeto = null, $msg = null) {
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
        
        <form action="?p=altproj" method="post">

            <input required type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input required type="hidden" name="id" value="<?= $projeto ? $projeto->__get('id') : '' ?>">

            <label for="nome">Nome: </label>
            <input required type="text" name="nome" id="nome" value="<?= $projeto ? $projeto->__get('nome') : '' ?>">
    
            <label for="prioridade">Prioridade: </label>
            <input required type="text" name="prioridade" id="prioridade" value="<?= $projeto ? $projeto->__get('prioridade') : '' ?>">
    
            <label for="dificuldade">Dificuldade: </label>
            <input required type="text" name="dificuldade" id="dificuldade" value="<?= $projeto ? $projeto->__get('dificuldade') : '' ?>">
    
            <label for="status">Status: </label>
            <input required type="text" name="status" id="status" maxlength="11" value="<?= $projeto ? $projeto->__get('status') : '' ?>">
    
            <button type="submit">Salvar</button>
        </form>
        <?php
    }
}