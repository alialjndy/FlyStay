<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>اختبار الدفع - Stripe + client_secret</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://js.stripe.com/v3"></script>
    <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial;
            background: #f6f7fb;
            margin: 0;
        }

        .container {
            max-width: 640px;
            margin: 40px auto;
            background: #fff;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        h1 {
            margin-top: 0;
            font-size: 20px;
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
            background: #fff;
        }

        textarea {
            height: 86px;
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .btn {
            appearance: none;
            border: none;
            background: #1e90ff;
            color: #fff;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn:disabled {
            opacity: .5;
            cursor: not-allowed;
        }

        .muted {
            color: #666;
            font-size: 13px;
        }

        .card-box {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: #fbfbfd;
        }

        .status {
            margin-top: 12px;
            padding: 12px;
            border-radius: 10px;
        }

        .ok {
            background: #e9fbe9;
            color: #176b1a;
            border: 1px solid #c3efc3;
        }

        .err {
            background: #fdeaea;
            color: #9a1a1a;
            border: 1px solid #f1c2c2;
        }

        code {
            background: #f0f3f7;
            padding: 2px 6px;
            border-radius: 6px;
        }

        .small {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>اختبار الدفع باستخدام <code>client_secret</code></h1>

        <p class="muted">
            هذه الصفحة ستقوم بطلب <code>client_secret</code> من API لديك، ثم تؤكد الدفع ببطاقة تجريبية عبر Stripe.
        </p>

        <form id="setup-form">
            <label>عنوان الـ API (إن تركته كما هو، يفترض Laravel على 8000)</label>
            <input id="apiBase" value="http://localhost:8000" />

            <div class="row">
                <div>
                    <label>نوع الحجز (type)</label>
                    <select id="type">
                        <option value="hotel-booking">hotel-booking</option>
                        <option value="flight-booking">flight-booking</option>
                    </select>
                </div>
                <div>
                    <label>ID الحجز</label>
                    <input id="id" type="number" placeholder="مثال: 1" />
                </div>
            </div>

            <label>JWT Token (ضع التوكن الذي حصلت عليه من تسجيل الدخول)</label>
            <textarea id="jwt" placeholder="eyJ0eXAiOiJKV1QiLCJhbGciOi..."></textarea>

            <label>Stripe Publishable Key</label>
            <input id="pk"
                value="pk_test_51RlygFD7m2bA7gV0iN89qx7NDAFr3mOcMPPWT0wGKeRRWAt2r5NsRb1fDsw24BSARzu6Mr8EjMVzLQfJs8elaTFj00KJiCdjR2" />

            <div style="margin-top:14px; display:flex; gap:10px;">
                <button type="button" id="btnCreate" class="btn">1) إنشاء PaymentIntent (الحصول على
                    client_secret)</button>
                <button type="button" id="btnReset" class="btn" style="background:#6b7280;">تفريغ</button>
            </div>
        </form>

        <div id="clientSecretBox" style="display:none; margin-top:18px;">
            <label>Client Secret</label>
            <input id="clientSecret" readonly />
            <p class="small muted">سنستخدم هذا السر لتأكيد الدفع.</p>
        </div>

        <div id="cardSection" style="display:none; margin-top:18px;">
            <label>بيانات البطاقة (Stripe Card Element)</label>
            <div id="card-element" class="card-box"></div>
            <div style="margin-top:14px;">
                <button id="btnPay" class="btn">2) ادفع الآن</button>
            </div>
            <p class="small muted" style="margin-top:8px;">
                بطاقة اختبار: <code>4242 4242 4242 4242</code>, تاريخ صلاحية مستقبلي (مثلاً 12/34), CVC أي 3 أرقام.
            </p>
        </div>

        <div id="status" style="display:none;"></div>
    </div>

    <script>
        const apiBaseEl = document.getElementById('apiBase');
        const typeEl = document.getElementById('type');
        const idEl = document.getElementById('id');
        const jwtEl = document.getElementById('jwt');
        const pkEl = document.getElementById('pk');
        const btnCreate = document.getElementById('btnCreate');
        const btnReset = document.getElementById('btnReset');
        const clientSecretBox = document.getElementById('clientSecretBox');
        const clientSecretEl = document.getElementById('clientSecret');
        const cardSection = document.getElementById('cardSection');
        const btnPay = document.getElementById('btnPay');
        const statusBox = document.getElementById('status');

        let stripe = null;
        let elements = null;
        let cardElement = null;
        let currentClientSecret = null;

        function showStatus(message, ok = true) {
            statusBox.style.display = 'block';
            statusBox.className = 'status ' + (ok ? 'ok' : 'err');
            statusBox.textContent = message;
        }

        function clearStatus() {
            statusBox.style.display = 'none';
            statusBox.textContent = '';
        }

        function resetAll() {
            clearStatus();
            clientSecretBox.style.display = 'none';
            cardSection.style.display = 'none';
            clientSecretEl.value = '';
            currentClientSecret = null;

            if (cardElement) {
                cardElement.unmount();
                cardElement = null;
            }
            elements = null;
            stripe = null;
        }

        btnReset.addEventListener('click', resetAll);

        btnCreate.addEventListener('click', async () => {
            clearStatus();

            const apiBase = apiBaseEl.value.trim().replace(/\/+$/, '');
            const type = typeEl.value;
            const id = idEl.value.trim();
            const jwt = jwtEl.value.trim();
            const pk = pkEl.value.trim();

            if (!apiBase || !id || !jwt || !pk) {
                showStatus('رجاءً عَبّئ جميع الحقول (API/ID/JWT/Publishable Key).', false);
                return;
            }

            btnCreate.disabled = true;
            try {
                const res = await fetch(`${apiBase}/api/payments/${type}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + jwt
                    },
                    body: JSON.stringify({})
                });
                // const text = await res.text(); // بدل res.json()
                // console.log(text); // سيظهر HTML أو JSON

                const json = await res.json();
                if (json.status !== 'success') {
                    throw new Error(json.message || 'فشل إنشاء PaymentIntent');
                }

                const clientSecret = Array.isArray(json.data) ? json.data[0] : json.data;
                if (!clientSecret || typeof clientSecret !== 'string') {
                    throw new Error('لم يتم استلام client_secret. تأكد أن المستخدم Customer وأن الحجز لك.');
                }

                currentClientSecret = clientSecret;
                clientSecretEl.value = clientSecret;
                clientSecretBox.style.display = 'block';

                stripe = Stripe(pk);
                elements = stripe.elements();
                cardElement = elements.create('card');
                cardElement.mount('#card-element');

                cardSection.style.display = 'block';
                showStatus('تم إنشاء PaymentIntent بنجاح. تستطيع الدفع الآن.', true);
            } catch (err) {
                showStatus(err.message, false);
            } finally {
                btnCreate.disabled = false;
            }
        });

        btnPay.addEventListener('click', async () => {
            clearStatus();

            if (!stripe || !cardElement || !currentClientSecret) {
                showStatus('لم يتم التهيئة بشكل صحيح. أنشئ PaymentIntent أولاً.', false);
                return;
            }

            btnPay.disabled = true;
            try {
                // التصحيح هنا - استخدام currentClientSecret بدلاً من clientSecret
                const { error, paymentIntent } = await stripe.confirmCardPayment(currentClientSecret, {
                    payment_method: {
                        card: cardElement,
                    }
                });

                if (error) {
                    throw new Error(error.message || 'فشل التأكيد');
                }

                if (paymentIntent && paymentIntent.status === 'succeeded') {
                    showStatus('تم الدفع بنجاح ✅ PaymentIntent: ' + paymentIntent.id, true);
                } else {
                    showStatus('حالة غير متوقعة: ' + (paymentIntent?.status || 'بدون حالة'), false);
                }
            } catch (err) {
                showStatus(err.message, false);
            } finally {
                btnPay.disabled = false;
            }
        });
    </script>
</body>

</html>