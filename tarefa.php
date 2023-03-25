<?php
    class Tarefa {
        private $id = 0;
        private $nomeTarefa = null;
        private $descricao = null;

        // ID
        public function setId(int $id) :void
        {
            $this->id =  $id;

        }
        public function obterId() :int
        {
            return $this->id;

        }

        // Nome da Tarefa
        public function setNomeTarefa(string $nomeTarefa) :void
        {
            $this->nomeTarefa =  $nomeTarefa;

        }
        public function obterNomeTarefa() :string
        {
            return $this->nomeTarefa;

        }

        // Descrição da tarefa
        public function setDescricao(string $descricao) :void
        {
            $this->descricao =  $descricao;

        }
        public function obterDescricao() :string
        {
            return $this->descricao;

        }

        // Conexao no banco
        private function conectaDB() :\PDO
        {
            return new \PDO("mysql:host=localhost;
                                   dbname=tarefasdb",
                                   "root",
                                   "");
            
        }


        // Query criar
        public function criar() :array
        {
            $abreBanco = $this->conectaDB();
            
            // Query
            $declaracao = $abreBanco->prepare("INSERT INTO tarefa
                                              VALUES (NULL, :_nome, :_descricao)");                             
            // Bind dos valores
            $declaracao->bindValue(":_nome", $this->obterNomeTarefa(), \PDO::PARAM_STR);
            $declaracao->bindValue(":_descricao", $this->obterDescricao(), \PDO::PARAM_STR);

            // Pegao o ID de da tarefa salva
            if ($declaracao->execute()) {
                $this->setId($abreBanco->lastInsertId());
                return $this->ler();
            }
            return [];                                   
        }

        // Query ler
        public function ler() :array
        {
            $abreBanco = $this->conectaDB();

            // Pesquisa em caso de NÃO ser informado ID 
            if ($this->obterId() === 0) {         // Não usar SELECT * para não pesar no banco
                $declaracao = $abreBanco->prepare("SELECT id, nomeTarefa, descricao 
                                                   FROM tarefa");                              
                if ($declaracao->execute()) {
                    return $declaracao->fetchAll(\PDO::FETCH_ASSOC); 
                }
            // Pesquisa em caso de ser informado ID 
            } else if ($this->obterId() > 0 ) {                                               
                $declaracao = $abreBanco->prepare("SELECT id, nomeTarefa, descricao 
                                                   FROM tarefa 
                                                   WHERE id = :_id"); 
                // Bind do ID
                $declaracao->bindValue(":_id", $this->obterId(), \PDO::PARAM_INT);                            

                if ($declaracao->execute()) {
                    return $declaracao->fetchAll(\PDO::FETCH_ASSOC);
                }  
            }
            return [];                                   
        }

        // Query atualizar
        public function atualizar() :array
        {
            $abreBanco = $this->conectaDB();
            $declaracao = $abreBanco->prepare("UPDATE tarefa 
                                               SET nomeTarefa = :_nome, descricao = :_descricao 
                                               WHERE id = :_id");
            // Bind dos valores 
            $declaracao->bindValue(":_id", $this->obterId(), \PDO::PARAM_INT);                                
            $declaracao->bindValue(":_nome", $this->obterNomeTarefa(), \PDO::PARAM_STR);
            $declaracao->bindValue(":_descricao", $this->obterDescricao(), \PDO::PARAM_STR);
    
            // Se ocorreu tudo corretamente, retorna query com todas tarefas 
            if ($declaracao->execute()) {
                $this->setId($abreBanco->lastInsertId());
                return $this->ler();
            }
            return [];                                   
        }

        // Query excluir
        public function excluir() :array
        {
            $person = $this->ler();
            $abreBanco = $this->conectaDB();
            $declaracao = $abreBanco->prepare("DELETE FROM tarefa 
                                               WHERE id = :_id");
            // Bind do ID 
            $declaracao->bindValue(":_id", $this->obterId(), \PDO::PARAM_INT);                                
    
            // Pegao o ID de da tarefa salva
            if ($declaracao->execute()) {
                return $person;
                
            }
            return [];                                   
        }
    }

?>