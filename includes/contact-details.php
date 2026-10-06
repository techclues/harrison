<?php
declare(strict_types=1);
// Firm contact details block, shared by contact.php and appointment.php.
require_once __DIR__ . '/site.php';
?>
<dl class="contact-details">
  <dt>Address</dt>
  <dd><?= h(CONTACT['address']) ?></dd>
  <dt>Telephone</dt>
  <dd><a href="tel:<?= h(CONTACT['phone_href']) ?>"><?= h(CONTACT['phone']) ?></a></dd>
  <dt>Mobile</dt>
  <dd><a href="tel:<?= h(CONTACT['mobile_href']) ?>"><?= h(CONTACT['mobile']) ?></a></dd>
  <dt>Email</dt>
  <dd><a href="mailto:<?= h(CONTACT['email']) ?>"><?= h(CONTACT['email']) ?></a></dd>
  <dt>Opening hours</dt>
  <dd><?= h(CONTACT['hours']) ?></dd>
</dl>
