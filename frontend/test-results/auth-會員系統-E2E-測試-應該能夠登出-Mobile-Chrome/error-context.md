# Page snapshot

```yaml
- generic [ref=e5]:
  - link "購物網站" [ref=e7] [cursor=pointer]:
    - /url: /
  - generic [ref=e9]:
    - heading "會員登入" [level=2] [ref=e10]
    - generic [ref=e11]:
      - generic [ref=e12]:
        - heading "會員登入" [level=2] [ref=e13]
        - paragraph [ref=e14]: 使用您的帳號登入以享受更多服務
      - generic [ref=e15]:
        - generic [ref=e16]: Email
        - textbox "Email" [ref=e17]:
          - /placeholder: your@email.com
      - generic [ref=e18]:
        - generic [ref=e19]: 密碼
        - textbox "密碼" [ref=e20]:
          - /placeholder: ••••••••
      - generic [ref=e21]:
        - generic [ref=e22]:
          - checkbox "記住我" [ref=e23]
          - generic [ref=e24]: 記住我
        - link "忘記密碼？" [ref=e25] [cursor=pointer]:
          - /url: "#"
      - button "登入" [ref=e26] [cursor=pointer]
      - generic [ref=e27]:
        - text: 還沒有帳號？
        - button "立即註冊" [ref=e28] [cursor=pointer]
  - link "← 返回首頁" [ref=e30] [cursor=pointer]:
    - /url: /
```