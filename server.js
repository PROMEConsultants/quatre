const express = require('express');
const bodyParser = require('body-parser');
const axios = require('axios');

const app = express();
const port = 3000;

app.use(bodyParser.json());

app.post('/chat', async (req, res) => {
    const userMessage = req.body.message;

    try {
        const response = await axios.post('https://api.openai.com/v1/engines/davinci-codex/completions', {
            prompt: userMessage,
            max_tokens: 150
        }, {
            headers: {
                'Authorization': `Bearer YOUR_OPENAI_API_KEY`,
                'Content-Type': 'application/json'
            }
        });

        const botMessage = response.data.choices[0].text.trim();
        res.json({ message: botMessage });
    } catch (error) {
        console.error(error);
        res.status(500).send('Error communicating with chatbot');
    }
});

app.listen(port, () => {
    console.log(`Server running at http://localhost:${port}`);
});
