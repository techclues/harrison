(() => {
  'use strict';
  // Navigation, focus behaviour and the enquiry forms. No dependency on animation libraries.
  document.documentElement.classList.remove('no-js');

  const header = document.querySelector('.site-header');
  const mobileButton = document.querySelector('[data-mobile-toggle]');
  const mobileMenu = document.getElementById('mobile-navigation');
  const desktopQuery = window.matchMedia('(min-width: 961px)');
  const canHover = window.matchMedia('(hover: hover)').matches;

  const setExpanded = (button, menu, open) => {
    if (!button || !menu) return;
    button.setAttribute('aria-expanded', String(open));
    menu.hidden = !open;
  };

  // Desktop dropdowns (Services mega-menu, About): one open at a time.
  const dropdowns = [...document.querySelectorAll('[data-dropdown]')].map(wrap => {
    const button = wrap.querySelector('[data-dropdown-toggle]');
    return { wrap, button, menu: document.getElementById(button?.getAttribute('aria-controls') || '') };
  }).filter(d => d.button && d.menu);

  const close = (d, returnFocus = false) => {
    if (d.menu.hidden) return;
    setExpanded(d.button, d.menu, false);
    if (returnFocus) d.button.focus();
  };
  const open = d => {
    dropdowns.forEach(other => { if (other !== d) close(other); });
    setExpanded(d.button, d.menu, true);
  };

  dropdowns.forEach(d => {
    d.button.addEventListener('click', () => (d.menu.hidden ? open(d) : close(d)));
    // Tabbing out of a dropdown closes it so it never traps or hides focus.
    d.wrap.addEventListener('focusout', event => {
      if (event.relatedTarget && !d.wrap.contains(event.relatedTarget)) close(d);
    });
    // Open on hover for mouse users; button and keyboard behaviour are unchanged.
    if (canHover) {
      let closeTimer;
      d.wrap.addEventListener('mouseenter', () => {
        clearTimeout(closeTimer);
        if (desktopQuery.matches) open(d);
      });
      d.wrap.addEventListener('mouseleave', () => { closeTimer = setTimeout(() => close(d), 180); });
    }
  });

  // Mobile menu and its nested lists.
  const subToggles = [...document.querySelectorAll('[data-mobile-sub-toggle]')];
  const closeSubs = () => subToggles.forEach(button => {
    setExpanded(button, document.getElementById(button.getAttribute('aria-controls')), false);
    button.textContent = '+';
  });
  const setMobile = isOpen => {
    setExpanded(mobileButton, mobileMenu, isOpen);
    document.body.classList.toggle('menu-open', isOpen);
    mobileButton?.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
    if (!isOpen) closeSubs();
  };
  mobileButton?.addEventListener('click', () => setMobile(mobileMenu.hidden));
  subToggles.forEach(button => button.addEventListener('click', () => {
    const list = document.getElementById(button.getAttribute('aria-controls'));
    const isOpen = list.hidden;
    setExpanded(button, list, isOpen);
    button.textContent = isOpen ? '−' : '+';
  }));
  // Mobile menu links close the menu so it is never left open on the next view.
  mobileMenu?.addEventListener('click', event => {
    if (event.target.closest('a')) setMobile(false);
  });

  document.addEventListener('click', event => {
    dropdowns.forEach(d => { if (!d.wrap.contains(event.target)) close(d); });
  });
  document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') return;
    dropdowns.forEach(d => close(d, d.wrap.contains(document.activeElement)));
    if (mobileMenu && !mobileMenu.hidden) {
      const inside = mobileMenu.contains(document.activeElement) || mobileButton === document.activeElement;
      setMobile(false);
      if (inside) mobileButton.focus();
    }
  });
  desktopQuery.addEventListener('change', event => {
    if (event.matches) setMobile(false);
    else dropdowns.forEach(d => close(d));
  });

  const onScroll = () => header?.classList.toggle('scrolled', window.scrollY > 12);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // Enquiry forms (Contact, Appointment): submit without leaving the page. Without JavaScript the
  // form posts normally and send.php redirects back with a status message.
  document.querySelectorAll('[data-enquiry-form]').forEach(form => {
    const status = form.querySelector('.form-status');
    const button = form.querySelector('[data-submit]');
    const label = button.dataset.label || button.textContent.trim();
    let revert = 0;

    const setStatus = (message, kind = '') => {
      if (!status) return;
      status.textContent = message;
      if (kind) status.dataset.kind = kind; else delete status.dataset.kind;
    };
    const labelOf = el => el.closest('.field')?.querySelector('label') ? el.closest('.field') : null;

    // Per-field red messages: shown next to what is missing, cleared as soon as it is fixed.
    const showFieldError = (el, message) => {
      const field = labelOf(el);
      if (!field) return;
      let note = field.querySelector('.field-error');
      if (!note) {
        note = document.createElement('p');
        note.className = 'field-error';
        note.id = `${el.id}-error`;
        field.append(note);
      }
      note.textContent = message;
      el.setAttribute('aria-invalid', 'true');
      el.setAttribute('aria-describedby', note.id);
    };
    const clearFieldError = el => {
      el.removeAttribute('aria-invalid');
      el.removeAttribute('aria-describedby');
      labelOf(el)?.querySelector('.field-error')?.remove();
    };
    const clearAllErrors = () => form.querySelectorAll('[aria-invalid]').forEach(clearFieldError);
    const clientMessage = el => {
      const v = el.validity;
      if (v.valueMissing) return el.type === 'checkbox' ? 'Please tick the box to agree.' : 'This field is required.';
      if (v.typeMismatch && el.type === 'email') return 'Please enter a valid email address.';
      if (v.rangeUnderflow || v.rangeOverflow) return 'Please choose a valid value.';
      return el.validationMessage || 'Please check this field.';
    };

    // Button: gold "Send" -> "Sending…" -> green "Sent", then back to normal.
    const setButton = state => {
      window.clearTimeout(revert);
      button.classList.toggle('is-sent', state === 'sent');
      button.classList.toggle('is-sending', state === 'sending');
      button.disabled = state === 'sending';
      button.textContent = state === 'sent' ? 'Sent' : state === 'sending' ? 'Sending…' : label;
      if (state === 'sent') revert = window.setTimeout(() => setButton('idle'), 8000);
    };
    if (button.classList.contains('is-sent')) revert = window.setTimeout(() => setButton('idle'), 8000); // after a no-JS send

    // Editing a field clears its error; starting a new message turns a green "Sent" back to normal.
    form.addEventListener('input', event => {
      if (event.target.hasAttribute?.('aria-invalid') && event.target.validity.valid) clearFieldError(event.target);
      if (button.classList.contains('is-sent')) setButton('idle');
    });
    form.addEventListener('change', event => {
      if (event.target.hasAttribute?.('aria-invalid') && event.target.validity.valid) clearFieldError(event.target);
    });

    form.addEventListener('submit', async event => {
      form.classList.add('was-checked');
      clearAllErrors();
      if (!form.checkValidity()) {
        event.preventDefault();
        const invalid = [...form.elements].filter(el => el.willValidate && !el.validity.valid);
        invalid.forEach(el => showFieldError(el, clientMessage(el)));
        setStatus('Please complete the fields marked in red.', 'error');
        invalid[0]?.focus();
        return;
      }
      if (!window.fetch || !window.FormData) return; // let the browser post it normally
      event.preventDefault();
      setButton('sending');
      setStatus('');
      try {
        const response = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
        const result = await response.json();
        if (result.ok) {
          form.reset();
          form.classList.remove('was-checked');
          setStatus(result.message, 'ok');
          setButton('sent');
        } else {
          const errors = result.errors || {};
          Object.keys(errors).forEach(name => form.elements[name] && showFieldError(form.elements[name], errors[name]));
          setStatus(Object.keys(errors).length ? 'Please complete the fields marked in red.' : result.message, 'error');
          form.elements[Object.keys(errors)[0]]?.focus();
          setButton('idle');
        }
      } catch {
        setStatus('Sorry, something went wrong. Please try again, or call us.', 'error');
        setButton('idle');
      }
    });
  });
})();
