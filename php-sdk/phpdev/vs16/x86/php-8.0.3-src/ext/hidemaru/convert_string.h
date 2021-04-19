/*
 * Copyright (c) 2017 Akitsugu Komiyama
 * under the Apache License Version 2.0
 */

#pragma once

#include <string>

using namespace std;

// utf8→utf16へ
std::wstring utf8_to_utf16(const std::string& text);

// utf16→utf8へ
std::string utf16_to_utf8(const std::wstring& text);

// cp932→utf16へ
std::wstring cp932_to_utf16(const std::string& text);

// utf16→cp932へ
std::string utf16_to_cp932(const std::wstring& text);

