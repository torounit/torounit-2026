# CLAUDE.md

このファイルは、Claude Code (claude.ai/code) がこのリポジトリで作業する際のガイダンスを提供します。

## 概要

torounit の個人サイト向け WordPress ブロックテーマ（FSE: フルサイト編集）。WordPress 7.1+ / PHP 8.0+ を対象としています。

## テーマの構成

**ビルドプロセスなし**のピュアなブロックテーマです。スタイリングはすべて `theme.json` とブロックマークアップ（HTML/PHP）で管理します。

- **[theme.json](theme.json)** — メイン設定ファイル。カラーパレット・スペーシングスケール・タイポグラフィ・レイアウト幅・コアブロックのグローバルスタイルを定義。
- **[style.css](style.css)** — 最小限のCSS。テーマメタデータヘッダーと小さなプログレッシブエンハンスメントのみ（プリプロセッサ不使用）。
- **[functions.php](functions.php)** — Google Fonts（Zen Kaku Gothic New・M PLUS 1 Code・Material Symbols Outlined）のエンキュー、投稿フォーマットサポート、コンテンツ幅の設定。
- **[templates/](templates/)** — Gutenberg ブロックマークアップで記述したフルページテンプレート。
- **[parts/](parts/)** — 再利用可能なテンプレートパーツ（`header.html`、`footer.html`）。
- **[patterns/](patterns/)** — ブロックパターン（PHPファイル、ドキュメントコメントにメタデータヘッダーを記述）。

## デザイントークン（theme.json）

**カラー**（oklch ベース、5段階）: `base` / `subtle` / `muted` / `dim` / `contrast`

**スペーシング**（フィボナッチ系スケール）: スラッグ `20`〜`80`、値は `0.5rem` × 1/2/3/5/8/13/21

**フォントファミリー**: `zen-kaku-gothic-new`（本文）、`m-plus-1-code`（コード・等幅）

**フォントサイズ**: `small`・`medium`（1rem 基準）・`large`・`x-large`・`xx-large`・`xxx-large` — すべて `medium` からの相対値

**レイアウト**: contentSize `850px`、wideSize `1290px`

## 開発上の注意

- npm/composer/ビルドステップなし — ファイルを直接編集し WordPress 環境で確認する。
- テンプレート・パーツは Gutenberg ブロックコメント構文（`<!-- wp:block-name {...} /-->`）を使用する。
- `.pen` ファイル（[design.pen](design.pen)）は Pencil デザインファイル — 読み書きには `pencil` MCP ツールを使用し、Read/Edit ツールは使わない。
- `theme.json` のスペーシングは `calc()` と `var()` を組み合わせて記述する。新しいサイズを追加する際はフィボナッチスケールのパターンに従う。
- フォントサイズの `line-height` は CSS `round()` で 1/4rem グリッドにスナップする（`theme.json` のグローバル CSS を参照）。

## リリースと自動更新

- バージョン管理は [release-please](https://github.com/googleapis/release-please) が行う。**コミットメッセージ（スカッシュマージ時は PR タイトル）は Conventional Commits 形式**（`feat:` / `fix:` / `chore:` など）で書く。
- `style.css` の `Version` は手で書き換えない。`x-release-please-start-version` / `x-release-please-end` で囲まれた行を release-please が更新する（`version.txt` と `.release-please-manifest.json` も同様）。
- main へのマージごとに Release PR が更新される。Release PR をマージするとタグ（`v` なし、例 `0.4.0`）と GitHub Release が作成される。
- 本番サイトでは [Git Updater](https://git-updater.com/) プラグインが `style.css` の `GitHub Theme URI` / `Primary Branch` ヘッダーを読み、最新タグのソース zip で更新する。Git Updater はタグと `Version` の一致を前提にしているので、`Version` は必ず release-please 経由で上げる。
- 配布されるのは GitHub のソース zip（`git archive` 相当）なので、配布に含めないファイルは [.gitattributes](.gitattributes) に `export-ignore` を追加する。
