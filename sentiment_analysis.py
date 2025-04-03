# sentiment_analysis.py
import nltk

import sys
from nltk.sentiment import SentimentIntensityAnalyzer

# Download the VADER lexicon
nltk.download('vader_lexicon')

# Initialize the VADER sentiment analyzer
sia = SentimentIntensityAnalyzer()

# Read input text from command line
text = sys.argv[1]

# Analyze sentiment
sentiment_scores = sia.polarity_scores(text)

# Determine sentiment based on compound score
if sentiment_scores['compound'] >= 0.05:
    print("Positive")
elif sentiment_scores['compound'] <= -0.05:
    print("Negative")
else:
    print("Neutral")
