const chatMessages = document.getElementById('chatMessages');
const agentForm = document.getElementById('agentForm');
const agentInput = document.getElementById('agentInput');

const responses = [
  {
    keys: ['product', 'offer', 'equipment', 'diagnostic', 'consumable', 'disposable'],
    reply: 'Innotech Medical supplies a range of medical equipment, diagnostic and laboratory systems, plus consumables and disposables for healthcare providers and institutions.'
  },
  {
    keys: ['contact', 'reach', 'call', 'phone', 'email', 'support'],
    reply: 'You can contact our team through the contact page or call our help desk at +92 331 6699992 for support and product inquiries.'
  },
  {
    keys: ['laboratory', 'lab', 'clinic', 'hospital', 'institution'],
    reply: 'We support hospitals, clinics, laboratories, and healthcare organizations with dependable equipment and technical guidance.'
  },
  {
    keys: ['quote', 'price', 'pricing', 'quotation', 'cost'],
    reply: 'For pricing and quotations, please share the product name, quantity, and your preferred delivery location so our team can respond with a tailored estimate.'
  },
  {
    keys: ['address', 'location', 'gulshan', 'badda'],
    reply: 'Our team is available to assist with regional queries and can guide you through the contact process for your location.'
  }
];

function appendMessage(text, type) {
  const wrapper = document.createElement('div');
  wrapper.className = `message ${type}`;
  wrapper.textContent = text;
  chatMessages.appendChild(wrapper);
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

function getReply(question) {
  const normalized = question.toLowerCase();
  const match = responses.find((item) => item.keys.some((key) => normalized.includes(key)));
  return match ? match.reply : 'I can help with products, support, pricing, and contact details. Try asking about a specific equipment category or service.';
}

function handleSubmission(question) {
  if (!question.trim()) return;
  appendMessage(question, 'user');
  agentInput.value = '';
  window.setTimeout(() => {
    appendMessage(getReply(question), 'bot');
  }, 300);
}

agentForm.addEventListener('submit', (event) => {
  event.preventDefault();
  handleSubmission(agentInput.value);
});

document.querySelectorAll('.quick-pill').forEach((button) => {
  button.addEventListener('click', () => {
    handleSubmission(button.dataset.prompt);
  });
});
