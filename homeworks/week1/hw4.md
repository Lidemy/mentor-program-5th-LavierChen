## 跟你朋友介紹 Git

在日常生活中，我們其實一直都在執行版本控制的操作。例如：寫一篇文案，很少一次就寫得很滿意，在增刪修改的過程中，如果想要保留文案修改前的內容，執行「另存新檔」的動作，就是在做版本控制。  
Git 就是用來進行版本控制的軟體，由電腦自動保存檔案的歷史紀錄。除了一人作業能進行版本控制，多人協作更是要能實踐版本控制，達到開發過程各自獨立的目的。

---

### Git 基本指令

1. `git init`：初始化當前所在的目錄，對目錄進行版本控制
2. `git status`：檢查當前目錄版本控制的狀態
   - untracked（未追蹤的）：檔案尚未被加入版控
   - staged（暫存區）：等待被 commit 的檔案
   - unmodified（未修改的）：檔案第一次被加入
   - modified（已修改的）：檔案已經被編輯過
3. `git add <file>`：選擇檔案加入版本控制
4. `git commit -m "message"`：新建版本
5. `git log`：查閱版本歷史紀錄
6. `git checkout <版本號碼>`：切換到特定版本
   - `git checkout master`：切換到最新版本

---

Git 的功能 branch，是程式開發的重要元素，目的是讓開發過程能各自獨立的執行。

### branch 相關指令

1. `git branch -v`：查看目前所在的分支
2. `git branch <branchName>`：新增分支
3. `git branch -d <branchName>`：刪除分支
4. `git checkout <branchName>`：切換分支
5. `git merge <branchName>`：把分支合併至當前所在的分支

---

## Git 與 Github

Git 是用來版本控制的程式。  
Github 是提供存放 Git repository 的空間，透過 GUI 介面，可以查看專案版本控制的歷史紀錄。

### Git 進階指令

1. `git remote`：為 host 端的 repository 建立一個 Github 遠端的 repository
2. `git push`：把 host 端的檔案同步到遠端
3. `git pull`：把遠端的檔案同步到 host 端
4. `git clone`：從 Github 遠端下載 repository 至 host 端
