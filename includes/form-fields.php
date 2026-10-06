<?php
declare(strict_types=1);
/*
 * Shared form plumbing for the Contact and Appointment forms. Expects $formType ('contact' or
 * 'appointment') and $formSubmitLabel. Renders: hidden type + signed token, the honeypot, the
 * consent checkbox, the submit button and the status message.
 */
require_once __DIR__ . '/forms.php';
[$statusKind, $statusText] = form_status_message($_GET['status'] ?? null);
?>
        <input type="hidden" name="form" value="<?= h($formType) ?>">
        <input type="hidden" name="ts" value="<?= h(form_token()) ?>">
        <div class="hp" aria-hidden="true"><label>Leave this field empty <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="field field--consent">
          <label class="consent" for="<?= h($formType) ?>-consent"><input id="<?= h($formType) ?>-consent" name="consent" type="checkbox" value="yes" required aria-required="true"><span>I agree to Harrison Accountants using these details to reply to my message. See our <a href="privacy-cookies.php">Privacy &amp; Cookies Policy</a>. <span class="req" aria-hidden="true">*</span></span></label>
        </div>
        <button class="btn btn-gold<?= $statusKind === 'ok' ? ' is-sent' : '' ?>" type="submit" data-submit data-label="<?= h($formSubmitLabel) ?>"><?= $statusKind === 'ok' ? 'Sent' : h($formSubmitLabel) ?></button>
        <p class="form-status" id="form-status" role="status" aria-live="polite"<?= $statusKind !== '' ? ' data-kind="' . h($statusKind) . '"' : '' ?>><?= h($statusText) ?></p>
