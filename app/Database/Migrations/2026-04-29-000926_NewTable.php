<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class NewTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'auto_increment' => true,
                'comment' => 'Número sequencial único do registro.',
            ],
            'uuid' => [
                'type' => 'VARCHAR',
                'constraint' => 42,
                'null' => false,
                'default' => new RawSql('(uuid())'),
                'comment' => 'Identificador do registro.',
            ],
            'data_ins' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
                'comment' => 'Data e hora de inserção do registro.',
            ],
            'data_upd TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT "Data e hora da última alteração do registro."', // Raw SQL inline
            'data_del' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'default' => null,
                'comment' => 'Data e hora de deleção do registro. (Os registros com este campo preenchido, não serão mostrados no sistema).',
            ],
            'id_user_ins' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Número de identificação do usuário que inseriu o registro.',
            ],
            'id_user_del' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Número de identificação do usuário que deletou o registro.',
            ],
            'id_company' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Número de identificação do cadastro da pessoa referência.',
            ],
            'id_contract_classification' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Número de identificação da classificação do produto.',
            ],
            'person_type' => [
                'type' => 'ENUM',
                'constraint' => ['pf', 'pj', 'mei', 'sn'],
                'default' => 'pf',
                'comment' => 'Tipo da pessoa: pf=pessoa física; pj=jurídica; mei=MEI; sn=Simples Nacional.',
            ],
            'percentual_iof_additional' => [
                'type' => 'DECIMAL',
                'constraint' => '20,2',
                'default' => '0.00',
                'comment' => 'Percentual cobrado de IOF adicional.',
            ],
            'percentual_iof_daily' => [
                'type' => 'DECIMAL',
                'constraint' => '20,5',
                'default' => '0.00000',
                'comment' => 'Percentual cobrado de IOF ao dia.',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('data_del');
        $this->forge->addKey('person_type');
        $this->forge->addKey('id_company');
        $this->forge->addKey('id_contract_classification');

        $this->forge->createTable('reg_company_iof', true);
    }

    public function down()
    {
        //
    }
}
