# Prompts Repository & AI Engineering Hub - Noksha (নকশা)

Welcome to the `prompts/` directory for **Noksha (নকশা)**.

---

## 🎯 Purpose of This Directory

This directory serves as the centralized repository for storing system prompts, AI assistant instructions, code generation templates, and engineering guidelines used throughout the lifecycle of the Noksha project.

As Noksha incorporates AI-powered capabilities (such as auto-tagging graphic templates, generating product descriptions, and enabling natural language asset search), structured prompts will be systematically tracked and documented here.

---

## 📁 Directory Structure & Categories (Future Organization)

Future prompts stored in this folder will be categorized as follows:

```text
prompts/
├── README.md                 # This overview document
├── system/                   # System-level AI assistant persona & architectural prompts
├── code-gen/                 # Prompts used for generating backend, frontend, and SQL code
├── ai-features/              # Prompts driving Noksha's runtime AI features:
│   ├── auto-tagging.md       # Prompts for AI-driven graphic asset tagging
│   ├── search-intent.md      # Prompts for converting user queries into SQL/Search filters
│   └── desc-generator.md     # Prompts for generating product marketplace descriptions
└── testing/                  # Prompts used to generate unit tests, test data, & mock payloads
```

---

## 📜 Prompt Document Format Standard

When saving new prompts to this directory, please adhere to the following Markdown template:

```markdown
# [Prompt Title / Identifier]

- **Category:** [System / Code-Gen / AI-Features / Testing]
- **Target Model:** [Gemini / Claude / GPT-4]
- **Version:** [e.g., 1.0.0]
- **Date Added:** [YYYY-MM-DD]

### Purpose
Brief explanation of what this prompt accomplishes.

### System / Context Prompt
```text
Insert system prompt text here...
```

### User Input Structure
```text
Insert expected input format or variables here...
```

### Expected Output Format
```json
{
  "example_key": "example_value"
}
```
```

---

## 🚀 Guidelines for Adding Prompts

1. **Version Control:** Document prompt changes and updates to track AI performance iterations.
2. **Security:** Do **NOT** store private API keys, user secrets, or database credentials inside prompt files.
3. **Reproducibility:** Clearly record parameters such as temperature, system instructions, and target models alongside the prompt text.
