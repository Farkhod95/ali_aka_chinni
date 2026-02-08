<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%my_total_debt_history}}`.
 */
class m210108_093458_add_2_summ_column_to_my_total_debt_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        $this->addColumn('{{%my_total_debt_history}}', 'exchange_rate', $this->float());
        $this->addColumn('{{%my_total_debt_history}}', 'all_summ_dollar', $this->float());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%my_total_debt_history}}', 'exchange_rate');
        $this->dropColumn('{{%my_total_debt_history}}', 'all_summ_dollar');
    }
}
