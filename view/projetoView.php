<?php
namespace App\view;

use App\dal\ProjetoDao;

class ProjetoView{

    public static function listar($projetos, $msg = null){
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
                <th>Nome</th>
                <th>Prioridade: 1 a 5</th>
                <th>Dificuldade: 1 a 5</th>
                <th>Data de Inicio</th>
                <th>Data de Conclusão</th>
                <th>Status</th>
                <?php if(isset($_SESSION['username']) && $_SESSION['username']): ?>
                    <th>Ação</th>
                <?php endif; ?>
                <?php if(isset($_SESSION['username']) && $_SESSION['user_group'] == 2): ?>
                    <th>Ação</th>
                <?php endif; ?>
            </tr>
            <?php foreach($projetos as $projeto): ?>
            <tr>
                <td><?= $projeto->__get("nome") ?></td>
                <td><?= $projeto->__get("prioridade") ?></td>
                <td><?= $projeto->__get("dificuldade") ?></td>
                <td><?= $projeto->__get("data_inicio") ?></td>
                <td><?= $projeto->__get("data_fim") ?></td>
                <td><?= $projeto->__get("status") ?></td>
                <?php if(isset($_SESSION['username']) && $_SESSION['user_group'] == 2): ?>
                    <td><a href='?p=altproj&alt=<?= $projeto->__get("id")?>'>Alterar</a></td>
                    <td><a href='?p=delproj&del=<?= $projeto->__get("id")?>'>Excluir</a></td>
                <?php elseif(isset($_SESSION['username'])): ?>
                <td><a href='?p=projects&join=<?= $_SESSION["user_id"] ?? '' ?>'>Entrar</a></td>
                <?php endif;?>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php
    }

}