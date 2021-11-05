// const stripe = Stripe('{{stripePublicKey}}');
// initialize();

// document
//   .querySelector("#payment-form")
//   .addEventListener("submit", handleSubmit);

// // Fetches a payment intent and captures the client secret
// async function initialize() {
//   const { clientSecret } =  "{{clientSecret}}" ;

//   elements = stripe.elements({ clientSecret : "{{ clientSecret }}" });

//   const paymentElement = elements.create("payment");
//   paymentElement.mount("#payment-element");
// }

// async function handleSubmit(e) {
//   e.preventDefault();
//   setLoading(true);

//   const { error } = await stripe.confirmPayment({
//     elements,
//     confirmParams: {
//       // Make sure to change this to your payment completion page
//       return_url: "{{url('purchase_payment_success', {'id': purchase.id})}}",
//     },
//   });

//   setLoading(false);
// }

// function setLoading(isLoading) {
// if (isLoading) {
//   // Disable the button and show a spinner
//   document.querySelector("#submit").disabled = true;
//   document.querySelector("#spinner").classList.remove("hidden");
//   document.querySelector("#button-text").classList.add("hidden");
// } else {
//    document.querySelector("#submit").disabled = false;
//   document.querySelector("#spinner").classList.add("hidden");
//    document.querySelector("#button-text").classList.remove("hidden");
//  }
// }

const stripe = Stripe(stripePublicKey);
// const stripe = Stripe('{{stripePublicKey}}');
initialize();

document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);

// Fetches a payment intent and captures the client secret
async function initialize() {

  elements = stripe.elements({ clientSecret : idClientSecret });

  const paymentElement = elements.create("payment");
  paymentElement.mount("#payment-element");
}

async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);

  const { error } = await stripe.confirmPayment({
    elements,
    confirmParams: {
      // Make sure to change this to your payment completion page
      return_url: redirectAfterSuccessUrl,
    },
  });

  setLoading(false);
}

function setLoading(isLoading) {
if (isLoading) {
  // Disable the button and show a spinner
  document.querySelector("#submit").disabled = true;
  document.querySelector("#spinner").classList.remove("hidden");
  document.querySelector("#button-text").classList.add("hidden");
} else {
   document.querySelector("#submit").disabled = false;
  document.querySelector("#spinner").classList.add("hidden");
   document.querySelector("#button-text").classList.remove("hidden");
 }
}
