/**
 * Joey AI — Frontend Chat Widget Controller
 * Handles UI interactions, API communication, and lead capture.
 */
(function () {
    'use strict';

    // ──── State ────────────────────────────────────────────────
    const state = {
        isOpen: false,
        isWelcome: true,
        history: [],
        isSending: false,
        quickCards: [],
    };

    // ──── DOM References ───────────────────────────────────────
    let launcher, chatContainer, chatBody, chatInput, sendBtn;
    let leadForm, leadFormInner;

    // ──── Init ─────────────────────────────────────────────────
    function init() {
        launcher       = document.getElementById('joeyLauncher');
        chatContainer  = document.getElementById('joeyChatContainer');
        chatBody       = document.getElementById('joeyChatBody');
        chatInput      = document.getElementById('joeyChatInput');
        sendBtn        = document.getElementById('joeySendBtn');
        leadForm       = document.getElementById('joeyLeadForm');

        if (!launcher || !chatContainer) return;

        // Parse quick cards from data attributes
        try {
            const raw = chatContainer.getAttribute('data-quick-cards');
            if (raw) state.quickCards = JSON.parse(raw);
        } catch (e) {
            state.quickCards = [];
        }

        // Events
        launcher.addEventListener('click', toggleChat);
        document.getElementById('joeyCloseBtn').addEventListener('click', toggleChat);
        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Lead form events
        document.getElementById('joeyLeadSubmit').addEventListener('click', submitLead);
        document.getElementById('joeyLeadCancel').addEventListener('click', closeLeadForm);

        renderWelcome();
    }

    // ──── Toggle Chat ──────────────────────────────────────────
    function toggleChat() {
        state.isOpen = !state.isOpen;

        if (state.isOpen) {
            chatContainer.classList.add('active');
            launcher.classList.add('active');
            // Force reflow for animation
            requestAnimationFrame(() => {
                chatContainer.style.opacity = '1';
                chatContainer.style.transform = 'translateY(0) scale(1)';
            });
            chatInput.focus();
        } else {
            chatContainer.style.opacity = '0';
            chatContainer.style.transform = 'translateY(20px) scale(0.95)';
            setTimeout(() => {
                chatContainer.classList.remove('active');
            }, 300);
            launcher.classList.remove('active');
        }
    }

    // ──── Render Welcome Screen ────────────────────────────────
    function renderWelcome() {
        let cardsHtml = '';
        const defaultCards = [
            { icon: '🌴', title: 'Bali packages', desc: 'Ubud jungle, beachfront, temples' },
            { icon: '✈️', title: 'Singapore packages', desc: 'Gardens by the bay, Sentosa' },
            { icon: '🏝️', title: 'Maldives luxury', desc: 'Overwater villas, retreats' },
            { icon: '🗻', title: 'Japan explorer', desc: 'Tokyo, Kyoto, Mt Fuji' },
            { icon: '🏔️', title: 'Kerala getaway', desc: 'Tea gardens, backwaters' },
            { icon: '🔍', title: 'Custom travel', desc: 'Plan a tailormade holiday' },
        ];

        const cards = state.quickCards.length > 0 ? state.quickCards : defaultCards;

        cards.forEach(function (card) {
            cardsHtml += '<div class="joey-quick-card" data-query="' + escapeAttr(card.title) + '">' +
                '<p class="joey-quick-card-title">' + (card.icon || '') + ' ' + escapeHtml(card.title) + '</p>' +
                '<p class="joey-quick-card-desc">' + escapeHtml(card.desc) + '</p>' +
                '</div>';
        });

        const avatarSrc = chatContainer.getAttribute('data-avatar') || '';

        chatBody.innerHTML =
            '<div class="joey-welcome">' +
            (avatarSrc ? '<img src="' + escapeAttr(avatarSrc) + '" alt="Joey AI" class="joey-welcome-avatar">' : '') +
            '<h3 class="joey-welcome-title">Hey, I\'m Joey 🦘</h3>' +
            '<p class="joey-welcome-sub">Your personal travel advisor. How can I help?</p>' +
            '<div class="joey-quick-grid">' + cardsHtml + '</div>' +
            '</div>';

        // Attach quick card click handlers
        chatBody.querySelectorAll('.joey-quick-card').forEach(function (card) {
            card.addEventListener('click', function () {
                var query = this.getAttribute('data-query');
                if (query) {
                    state.isWelcome = false;
                    chatBody.innerHTML = '';
                    appendMessage('user', 'Tell me about ' + query);
                    callAPI('Tell me about ' + query);
                }
            });
        });

        state.isWelcome = true;
    }

    // ──── Send Message ─────────────────────────────────────────
    function sendMessage() {
        var text = chatInput.value.trim();
        if (!text || state.isSending) return;

        if (state.isWelcome) {
            state.isWelcome = false;
            chatBody.innerHTML = '';
        }

        appendMessage('user', text);
        chatInput.value = '';
        callAPI(text);
    }

    // ──── Append Message Bubble ────────────────────────────────
    function appendMessage(role, content) {
        var div = document.createElement('div');
        div.className = 'joey-message ' + role;

        var bubble = document.createElement('div');
        bubble.className = 'joey-bubble';

        if (role === 'ai') {
            bubble.innerHTML = formatMarkdown(content);
        } else {
            bubble.textContent = content;
        }

        div.appendChild(bubble);
        chatBody.appendChild(div);
        scrollToBottom();

        state.history.push({
            role: role === 'user' ? 'user' : 'assistant',
            content: content,
        });
    }

    // ──── Show/Hide Typing ─────────────────────────────────────
    function showTyping() {
        var div = document.createElement('div');
        div.className = 'joey-typing';
        div.id = 'joeyTyping';
        div.innerHTML =
            '<span class="joey-typing-dot"></span>' +
            '<span class="joey-typing-dot"></span>' +
            '<span class="joey-typing-dot"></span>';
        chatBody.appendChild(div);
        scrollToBottom();
    }

    function hideTyping() {
        var el = document.getElementById('joeyTyping');
        if (el) el.remove();
    }

    // ──── Call AI Chat API ─────────────────────────────────────
    function callAPI(userMessage) {
        state.isSending = true;
        sendBtn.disabled = true;
        showTyping();

        var basePath = chatContainer.getAttribute('data-api-base') || '';

        fetch(basePath + '/api/ai-chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                message: userMessage,
                history: state.history.slice(0, -1), // exclude the just-added user msg
            }),
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                hideTyping();
                state.isSending = false;
                sendBtn.disabled = false;

                if (data.success && data.reply) {
                    appendMessage('ai', data.reply);

                    // Check if the reply triggers lead capture
                    checkLeadTrigger(data.reply);
                } else {
                    appendMessage('ai', data.error || 'Sorry, something went wrong. Please try again.');
                }
            })
            .catch(function () {
                hideTyping();
                state.isSending = false;
                sendBtn.disabled = false;
                appendMessage('ai', 'Oops! Couldn\'t reach Joey right now. Please try again in a moment.');
            });
    }

    // ──── Check if AI reply suggests lead capture ──────────────
    function checkLeadTrigger(reply) {
        var lower = reply.toLowerCase();
        var triggers = ['fill the form', 'share your details', 'your name', 'your email', 'your whatsapp',
            'connect you with', 'get in touch', 'share your contact', 'leave your details',
            'book this', 'request a quote', 'custom proposal'];
        for (var i = 0; i < triggers.length; i++) {
            if (lower.indexOf(triggers[i]) !== -1) {
                setTimeout(function () { openLeadForm(); }, 1200);
                return;
            }
        }
    }

    // ──── Lead Form ────────────────────────────────────────────
    function openLeadForm() {
        // Build the context summary from recent conversation
        var contextParts = [];
        state.history.forEach(function (msg) {
            if (msg.role === 'user') {
                contextParts.push(msg.content);
            }
        });
        var contextSummary = contextParts.slice(-3).join(' | ');
        leadForm.setAttribute('data-context', contextSummary);
        leadForm.classList.add('active');

        // Reset form
        document.getElementById('joeyLeadName').value = '';
        document.getElementById('joeyLeadEmail').value = '';
        document.getElementById('joeyLeadPhone').value = '';
        document.getElementById('joeyLeadFormInner').style.display = 'block';
        document.getElementById('joeyLeadSuccess').style.display = 'none';
    }

    function closeLeadForm() {
        leadForm.classList.remove('active');
    }

    function submitLead() {
        var name  = document.getElementById('joeyLeadName').value.trim();
        var email = document.getElementById('joeyLeadEmail').value.trim();
        var phone = document.getElementById('joeyLeadPhone').value.trim();
        var context = leadForm.getAttribute('data-context') || '';

        if (!name || !email || !phone) {
            alert('Please fill in all fields.');
            return;
        }

        var submitBtn = document.getElementById('joeyLeadSubmit');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        var basePath = chatContainer.getAttribute('data-api-base') || '';

        fetch(basePath + '/api/capture-ai-lead.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                client_name: name,
                work_email: email,
                whatsapp_line: phone,
                captured_context: context,
            }),
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Details';

                if (data.success) {
                    document.getElementById('joeyLeadFormInner').style.display = 'none';
                    document.getElementById('joeyLeadSuccess').style.display = 'block';
                    setTimeout(function () {
                        closeLeadForm();
                        appendMessage('ai', '✅ Your details have been captured! A Wanderoo travel expert will contact you soon on WhatsApp. 🦘✨');
                    }, 2500);
                } else {
                    alert(data.error || 'Something went wrong. Please try again.');
                }
            })
            .catch(function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Details';
                alert('Network error. Please try again.');
            });
    }

    // ──── Helpers ───────────────────────────────────────────────
    function scrollToBottom() {
        requestAnimationFrame(function () {
            chatBody.scrollTop = chatBody.scrollHeight;
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function escapeAttr(str) {
        if (!str) return '';
        return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function formatMarkdown(text) {
        if (!text) return '';
        // Basic markdown: bold, italic, line breaks, lists
        var html = escapeHtml(text);
        // Bold **text**
        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        // Italic *text*
        html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');
        // Unordered lists
        html = html.replace(/^[-•]\s+(.+)$/gm, '<li>$1</li>');
        html = html.replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>');
        // Numbered lists
        html = html.replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>');
        // Paragraphs
        html = html.replace(/\n\n/g, '</p><p>');
        html = html.replace(/\n/g, '<br>');
        html = '<p>' + html + '</p>';
        // Clean up empty tags
        html = html.replace(/<p><\/p>/g, '');
        return html;
    }

    // ──── Boot ─────────────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
