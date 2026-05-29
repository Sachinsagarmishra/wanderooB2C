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
        leadCaptured: false,
        leadDismissed: false,
        isResetting: false,
    };

    // ──── DOM References ───────────────────────────────────────
    let launcher, chatContainer, chatBody, chatInput, sendBtn;
    let leadForm, leadFormInner, backdrop, typingInterval;

    // ──── Init ─────────────────────────────────────────────────
    function init() {
        launcher       = document.getElementById('joeyLauncher');
        chatContainer  = document.getElementById('joeyChatContainer');
        chatBody       = document.getElementById('joeyChatBody');
        chatInput      = document.getElementById('joeyChatInput');
        sendBtn        = document.getElementById('joeySendBtn');
        leadForm       = document.getElementById('joeyLeadForm');
        backdrop       = document.getElementById('joeyBackdrop');

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

        // Backdrop click to close
        if (backdrop) {
            backdrop.addEventListener('click', toggleChat);
        }

        // New Chat reset button event
        const newChatBtn = document.getElementById('joeyNewChatBtn');
        if (newChatBtn) {
            newChatBtn.addEventListener('click', confirmNewChat);
        }

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
            if (backdrop) {
                backdrop.classList.add('active');
                requestAnimationFrame(() => {
                    backdrop.style.opacity = '1';
                });
            }
            // Force reflow for animation
            requestAnimationFrame(() => {
                chatContainer.style.opacity = '1';
                chatContainer.style.transform = 'translateX(0)';
            });
            chatInput.focus();
        } else {
            chatContainer.style.opacity = '0';
            chatContainer.style.transform = 'translateX(100%)';
            if (backdrop) {
                backdrop.style.opacity = '0';
                setTimeout(() => {
                    backdrop.classList.remove('active');
                }, 300);
            }
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

        const messages = [
            "🔍 We are fetching the best things for you, please wait...",
            "🌴 Scanning Wanderoo database for active tour packages...",
            "✈️ Curating custom romance, adventure, & luxury options...",
            "✨ Matching live rates, durations, and traveler reviews...",
            "🦘 Almost there! Generating your personalized holiday guide..."
        ];
        let currentMsgIdx = 0;

        div.innerHTML =
            '<div class="joey-typing-text">' + escapeHtml(messages[0]) + '</div>' +
            '<div class="joey-typing-dots">' +
                '<span class="joey-typing-dot"></span>' +
                '<span class="joey-typing-dot"></span>' +
                '<span class="joey-typing-dot"></span>' +
            '</div>';

        chatBody.appendChild(div);
        scrollToBottom();

        if (typingInterval) clearInterval(typingInterval);
        typingInterval = setInterval(function() {
            const textEl = div.querySelector('.joey-typing-text');
            if (textEl) {
                currentMsgIdx = (currentMsgIdx + 1) % messages.length;
                textEl.textContent = messages[currentMsgIdx];
            }
        }, 1500);
    }

    function hideTyping() {
        if (typingInterval) {
            clearInterval(typingInterval);
            typingInterval = null;
        }
        var el = document.getElementById('joeyTyping');
        if (el) el.remove();
    }

    // ──── Call AI Chat API (Streaming) ──────────────────────────
    function callAPI(userMessage) {
        state.isSending = true;
        sendBtn.disabled = true;
        showTyping();

        var basePath = chatContainer.getAttribute('data-api-base') || '';

        var isLeadSubmitted = sessionStorage.getItem('joey_lead_submitted') === 'true' || state.leadCaptured;

        fetch(basePath + '/api/ai-chat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                message: userMessage,
                history: state.history.slice(0, -1), // exclude the just-added user msg
                lead_submitted: isLeadSubmitted,
            }),
        })
        .then(async function (response) {
            hideTyping();
            if (!response.ok) {
                // If not ok, read text to see if it's a JSON error
                const errText = await response.text();
                try {
                    const parsedErr = JSON.parse(errText);
                    throw new Error(parsedErr.error || 'Network error.');
                } catch (e) {
                    throw new Error('Oops! Couldn\'t reach Joey right now. Please try again in a moment.');
                }
            }

            const reader = response.body.getReader();
            const decoder = new TextDecoder('utf-8');
            let firstChunkRead = false;
            let buffer = '';
            let fullReply = '';

            // Create placeholder message bubble for streaming response
            var div = document.createElement('div');
            div.className = 'joey-message ai';
            var bubble = document.createElement('div');
            bubble.className = 'joey-bubble';
            div.appendChild(bubble);
            chatBody.appendChild(div);
            scrollToBottom();

            while (true) {
                const { value, done } = await reader.read();
                if (done) break;

                const chunkText = decoder.decode(value, { stream: true });

                // Check if the response started as a JSON error object
                if (!firstChunkRead) {
                    firstChunkRead = true;
                    if (chunkText.trim().startsWith('{')) {
                        try {
                            const errData = JSON.parse(chunkText);
                            bubble.textContent = errData.error || errData.error?.message || 'AI service error.';
                            state.isSending = false;
                            sendBtn.disabled = false;
                            return;
                        } catch (e) { /* fall through to SSE stream */ }
                    }
                }

                buffer += chunkText;
                const lines = buffer.split('\n');
                buffer = lines.pop(); // keep last incomplete line in buffer

                for (const line of lines) {
                    const trimmed = line.trim();
                    if (!trimmed) continue;
                    if (trimmed.startsWith('data: ')) {
                        const dataStr = trimmed.slice(6);
                        if (dataStr === '[DONE]') {
                            break;
                        }
                        try {
                            const parsed = JSON.parse(dataStr);
                            const content = parsed.choices[0]?.delta?.content || '';
                            if (content) {
                                fullReply += content;
                                // Clean up any trailing assistant tags
                                let cleanedText = fullReply
                                    .replace(/<\/assistant>/gi, '')
                                    .replace(/<assistant>/gi, '')
                                    .replace(/<\/system>/gi, '')
                                    .replace(/<system>/gi, '')
                                    .replace(/<\/user>/gi, '')
                                    .replace(/<user>/gi, '');
                                bubble.innerHTML = formatMarkdown(cleanedText);
                                scrollToBottom();
                            }
                        } catch (err) {
                            // ignore incomplete chunk json parsing errors
                        }
                    }
                }
            }

            // Cleanup & Save to history
            state.isSending = false;
            sendBtn.disabled = false;

            let finalCleaned = fullReply
                .replace(/<\/assistant>/gi, '')
                .replace(/<assistant>/gi, '')
                .replace(/<\/system>/gi, '')
                .replace(/<system>/gi, '')
                .replace(/<\/user>/gi, '')
                .replace(/<user>/gi, '');

            state.history.push({
                role: 'assistant',
                content: finalCleaned,
            });

            // Trigger lead check
            checkLeadTrigger(finalCleaned);
        })
        .catch(function (error) {
            hideTyping();
            state.isSending = false;
            sendBtn.disabled = false;
            appendMessage('ai', error.message || 'Oops! Couldn\'t reach Joey right now. Please try again in a moment.');
        });
    }

    // ──── Check if AI reply suggests lead capture ──────────────
    function checkLeadTrigger(reply) {
        // Do not pop up repeatedly if already submitted or dismissed in this session
        if (state.leadCaptured || state.leadDismissed || sessionStorage.getItem('joey_lead_submitted')) {
            return;
        }
        var lower = reply.toLowerCase();
        var triggers = ['fill the form', 'share your details', 'your name', 'your email', 'your whatsapp',
            'connect you with', 'get in touch', 'share your contact', 'leave your details',
            'book this', 'request a quote', 'custom proposal'];
        for (var i = 0; i < triggers.length; i++) {
            if (lower.indexOf(triggers[i]) !== -1) {
                setTimeout(function () { openLeadForm(false); }, 1200);
                return;
            }
        }
    }

    // ──── Confirm New Chat Reset ────────────────────────────────
    function confirmNewChat() {
        if (confirm("Are you sure you want to start a new conversation? This will clear your current chat history.")) {
            // Open lead form to capture details before reset
            openLeadForm(true);
        }
    }

    // ──── Lead Form ────────────────────────────────────────────
    function openLeadForm(fromReset) {
        state.isResetting = !!fromReset;

        // Build the context summary from recent conversation
        var contextParts = [];
        state.history.forEach(function (msg) {
            if (msg.role === 'user') {
                contextParts.push(msg.content);
            }
        });
        var contextSummary = contextParts.slice(-3).join(' | ');
        if (state.isResetting) {
            contextSummary = "[NEW CHAT RESET] " + contextSummary;
        }
        
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
        state.leadDismissed = true;

        if (state.isResetting) {
            state.isResetting = false;
            state.history = [];
            renderWelcome();
            appendMessage('ai', 'Started a new session for you. How can I help you today? 🦘');
        }
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

        // WhatsApp number validation check
        const cleanPhone = phone.replace(/\D/g, '');
        if (cleanPhone.length < 10 || cleanPhone.length > 15) {
            alert('Please enter a valid WhatsApp number (at least 10 digits).');
            return;
        }

        // Reject identical digits (e.g. 0000000000, 9999999999)
        if (/^(\d)\1+$/.test(cleanPhone)) {
            alert('Please enter a valid WhatsApp number (avoid repeating digits).');
            return;
        }

        // Reject simple sequences (e.g. 1234567890, 9876543210)
        const seq = "01234567890123456789";
        const revSeq = "98765432109876543210";
        if (seq.indexOf(cleanPhone) !== -1 || revSeq.indexOf(cleanPhone) !== -1 || cleanPhone === '123456789' || cleanPhone === '12345678' || cleanPhone === '1234567890') {
            alert('Please enter a valid WhatsApp number (avoid dummy sequential runs).');
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
                    state.leadCaptured = true;
                    sessionStorage.setItem('joey_lead_submitted', 'true');

                    document.getElementById('joeyLeadFormInner').style.display = 'none';
                    document.getElementById('joeyLeadSuccess').style.display = 'block';
                    setTimeout(function () {
                        // Close form
                        leadForm.classList.remove('active');

                        if (state.isResetting) {
                            state.isResetting = false;
                            state.history = [];
                            renderWelcome();
                            appendMessage('ai', '✅ Details captured! I have started a fresh session for you. How can I help you today? 🦘✨');
                        } else {
                            appendMessage('ai', '✅ Your details have been captured! A Wanderoo travel expert will contact you soon on WhatsApp. 🦘✨');
                        }
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

        // Extract and replace consecutive PKG_CARD shortcodes
        const pkgCardGroups = [];
        let html = text.replace(/(?:\[PKG_CARD:\s*[^\n\]]+?\s*\]\s*)+/g, function(match) {
            const cardsHtml = [];
            const tagRegex = /\[PKG_CARD:\s*([^\n\]]*?)\s*\]/g;
            let tagMatch;

            while ((tagMatch = tagRegex.exec(match)) !== null) {
                const inner = tagMatch[1];
                const parts = inner.split('|');
                if (parts.length >= 4) {
                    const slug = parts[0].trim();
                    const title = parts[1].trim();
                    const price = parts[2].trim();
                    const duration = parts[3].trim();
                    const dest = parts[4] ? parts[4].trim() : '';
                    const heroImage = parts[5] ? parts[5].trim() : '';
                    const rating = parts[6] ? parts[6].trim() : '4.5';

                    const basePath = chatContainer.getAttribute('data-api-base') || '';
                    const url = basePath + '/' + encodeURIComponent(dest) + '/' + encodeURIComponent(slug);
                    
                    let imgUrl = heroImage ? (heroImage.startsWith('http') ? heroImage : (basePath + '/' + heroImage)) : (basePath + '/assets/img/hero-bg.webp');

                    const cardHtml = 
                        '<a href="' + escapeAttr(url) + '" class="joey-pkg-mini-card" target="_blank">' +
                        '<div class="joey-pkg-card-img-wrapper">' +
                        '<img src="' + escapeAttr(imgUrl) + '" alt="' + escapeAttr(title) + '" class="joey-pkg-card-img">' +
                        '</div>' +
                        '<div class="joey-pkg-card-body">' +
                        '<h4 class="joey-pkg-card-title">' + escapeHtml(title) + '</h4>' +
                        '<p class="joey-pkg-card-duration">⏱️ ' + escapeHtml(duration) + '</p>' +
                        '<div class="joey-pkg-card-footer">' +
                        '<span class="joey-pkg-card-price">' + escapeHtml(price) + '</span>' +
                        '<span class="joey-pkg-card-rating">★ ' + escapeHtml(rating) + '</span>' +
                        '</div>' +
                        '</div>' +
                        '</a>';

                    cardsHtml.push(cardHtml);
                }
            }

            if (cardsHtml.length > 0) {
                pkgCardGroups.push('<div class="joey-pkg-cards-grid">' + cardsHtml.join('') + '</div>');
                return '___PKG_CARD_GROUP_PLACEHOLDER_' + (pkgCardGroups.length - 1) + '___';
            }
            return '';
        });

        // Run standard markdown formatting on the remaining text
        html = escapeHtml(html);
        html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');
        html = html.replace(/^[-•]\s+(.+)$/gm, '<li>$1</li>');
        html = html.replace(/(<li>.*<\/li>)/gs, '<ul>$1</ul>');
        html = html.replace(/^\d+\.\s+(.+)$/gm, '<li>$1</li>');
        html = html.replace(/\n\n/g, '</p><p>');
        html = html.replace(/\n/g, '<br>');
        html = '<p>' + html + '</p>';
        html = html.replace(/<p><\/p>/g, '');

        // Restore PKG_CARD groups HTML
        pkgCardGroups.forEach(function(groupHtml, idx) {
            html = html.replace('___PKG_CARD_GROUP_PLACEHOLDER_' + idx + '___', groupHtml);
        });

        return html;
    }

    // ──── Boot ─────────────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
