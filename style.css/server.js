const express = require('express');
const bodyParser = require('body-parser');
const axios = require('axios');
const cors = require('cors');
const path = require('path');

const app = express();
const port = 3000;
const openaiApiKey ='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';

app.use(bodyParser.json());
app.use(cors());
app.use(express.static(path.join(_dirname, '../public')));


app.post('/chat', async (req, res) => {
    const userMessage = req.body.message;

    try {
        const response = await axios.post('https://api.openai.com/v1/completions', {
            model: 'text-davinci-003',
    
        
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
