<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = "Авторизация";

$fieldOptions1 = [
    'options' => ['class' => 'form-group'],
    'inputTemplate' => "{input}",
    'errorOptions' => ['class' => 'error-message']
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group'],
    'inputTemplate' => "{input}",
    'errorOptions' => ['class' => 'error-message']
];
?>

<div class="login-wrapper">
    <!-- LEFT SIDE - Gradient Info Section -->
    <div class="login-left">
        <div class="login-left-content">
            <div class="logo-area">
                <div class="logo-icon">
                    <i class="fa fa-shield"></i>
                </div>
                <h1><?= Html::encode(Yii::$app->name) ?></h1>
                <p>Xavfsiz va ishonchli boshqaruv tizimi. Platformamizga xush kelibsiz!</p>
            </div>
            
            <ul class="features-list">
                <li>
                    <i class="fa fa-check"></i>
                    <span>Yuqori darajadagi xavfsizlik</span>
                </li>
                <li>
                    <i class="fa fa-check"></i>
                    <span>Tez va qulay interfeys</span>
                </li>
                <li>
                    <i class="fa fa-check"></i>
                    <span>24/7 texnik yordam</span>
                </li>
                <li>
                    <i class="fa fa-check"></i>
                    <span>Ma'lumotlar shifrlangan</span>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- RIGHT SIDE - Login Form -->
    <div class="login-right">
        <div class="login-header">
            <h2>Tizimga kirish</h2>
            <p>Davom etish uchun ma'lumotlaringizni kiriting</p>
        </div>
        
        <div class="login-content">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'enableClientValidation' => false,
                'options' => ['class' => 'login-form-inner']
            ]); ?>

            <div class="form-group">
                <label class="form-label">
                    <i class="fa fa-user" style="margin-right: 6px;"></i>
                    <?= $model->getAttributeLabel('login') ?>
                </label>
                <div class="input-wrapper">
                    <i class="fa fa-user input-icon"></i>
                    <?= $form->field($model, 'username', $fieldOptions1)
                        ->label(false)
                        ->textInput([
                            'placeholder' => 'Login kiriting',
                            'class' => 'form-control',
                            'autocomplete' => 'username'
                        ]) ?>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fa fa-lock" style="margin-right: 6px;"></i>
                    <?= $model->getAttributeLabel('parol') ?>
                </label>
                <div class="input-wrapper">
                    <i class="fa fa-lock input-icon"></i>
                    <?= $form->field($model, 'password', $fieldOptions2)
                        ->label(false)
                        ->passwordInput([
                            'placeholder' => 'Parol kiriting',
                            'class' => 'form-control',
                            'autocomplete' => 'current-password'
                        ]) ?>
                </div>
            </div>

            <div class="login-actions">
                <?= Html::submitButton(
                    '<span>Kirish</span> <i class="fa fa-arrow-right" style="margin-left: 8px;"></i>', 
                    [
                        'class' => 'btn-login',
                        'name' => 'login-button'
                    ]
                ) ?>
            </div>

            <div class="helper-text">
                <i class="fa fa-info-circle"></i>
                Muammo yuzaga kelsa, administrator bilan bog'laning
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<style>
    /* Error message styling */
    .error-message {
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        display: block;
        animation: shake 0.3s ease;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .has-error .form-control {
        border-color: #ef4444 !important;
        background-color: #fef2f2 !important;
    }
    
    .has-error .input-icon {
        color: #ef4444;
    }
    
    /* Success state */
    .has-success .form-control {
        border-color: #10b981;
    }
    
    /* Additional animations */
    .form-group {
        animation: fadeInUp 0.4s ease backwards;
    }
    
    .form-group:nth-child(1) {
        animation-delay: 0.1s;
    }
    
    .form-group:nth-child(2) {
        animation-delay: 0.2s;
    }
    
    .login-actions {
        animation: fadeInUp 0.4s ease backwards;
        animation-delay: 0.3s;
    }
    
    .helper-text {
        animation: fadeInUp 0.4s ease backwards;
        animation-delay: 0.4s;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Button ripple effect */
    .btn-login {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    /* Focus states with smooth transitions */
    .input-wrapper.focused .input-icon {
        color: #667eea;
        transform: translateY(-50%) scale(1.1);
    }
    
    /* Loading state for button */
    .btn-login.loading {
        pointer-events: none;
        opacity: 0.7;
    }
    
    .btn-login.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 0.6s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    $(document).ready(function() {
        // Add loading state to form submission
        $('#login-form').on('submit', function() {
            var btn = $('.btn-login');
            btn.addClass('loading');
            btn.find('span').text('Yuklanmoqda...');
        });
        
        // Auto-focus first input
        $('input[name="LoginForm[username]"]').focus();
        
        // Add enter key support
        $('.form-control').on('keypress', function(e) {
            if (e.which === 13) {
                $('#login-form').submit();
            }
        });
        
        // Clear error on input
        $('.form-control').on('input', function() {
            $(this).closest('.form-group').removeClass('has-error');
            $(this).closest('.form-group').find('.error-message').fadeOut();
        });
    });
</script>
