
#include <windows.h>
#include <string>
#include <vector>
#include "convert_string.h"

using namespace std;

bool IsSTARTUNI_inline(DWORD byte4) {
	return (byte4 & 0xF4808000) == 0x04808000;
}

WCHAR inline GetUnicodeInText(char* pchSrc) {
	return MAKEWORD(
		(pchSrc[1] & 0x7F | ((pchSrc[3] & 0x01) << 7)),
		(pchSrc[2] & 0x7F | ((pchSrc[3] & 0x02) << 6))
	);
}


std::wstring DecodeOriginalEncodeVector(BYTE *original_encode_string) {
	try {
		// �Ǝ��G���R�[�h
		string str_original_encode = (char *)original_encode_string;

		const char* pstr = str_original_encode.c_str();

		// �ŏI�I�ȕԂ�l
		wstring result = L"";

		// �ꎞ�o�b�t�@�[�p
		string tmp_buffer = "";
		int len = (int)strlen(pstr);

		int lastcheckindex = len - sizeof(DWORD); // IsSTARTUNI_inline �ɂ� 4�o�C�g�K�v
		if (lastcheckindex < 0) {
			lastcheckindex = 0;
		}
		for (int i = 0; i < len; i++) {
			if (i <= lastcheckindex) {
				DWORD* pStarUni = (DWORD*)(&pstr[i]);
				if (pstr[i] == '\x1A' && IsSTARTUNI_inline(*pStarUni)) {
					if (tmp_buffer.length() > 0) {
						result += cp932_to_utf16(tmp_buffer);
						tmp_buffer.clear();
					}

					char* src = (char *)(&pstr[i]);
					wchar_t wch = GetUnicodeInText(src);
					i = i + 3; // 1�o�C�g�ł͂Ȃ�4�o�C�g���������̂ŁA�v�Z����
					result += wch;
					continue;
				}
			}
			tmp_buffer += pstr[i];
		}

		if (tmp_buffer.length() > 0) {
			result += cp932_to_utf16(tmp_buffer);
			tmp_buffer.clear();
		}

		return result;
	}
	catch (...) {
		OutputDebugString(L"�G���[:\nHmOrignalUnicodeDecodeError");
	}

	return L"";
}