PS1='\[\033]0;${PWD//[^[:ascii:]]/?}\007\]' # set window title
PS1="$PS1"'\n'                 # new line
PS1="$PS1"'\[\033[32m\]'       # change to green
PS1="$PS1"'\u@\h '             # user@host<space>
PS1="$PS1"'\[\033[35m\]'       # change to purple
PS1="$PS1"'\[\033[33m\]'       # change to brownish yellow
PS1="$PS1"'\w'                 # current working directory
if test -z "$WINELOADERNOEXEC"
then
        COMPLETION_PATH="/usr/share/bash-completion/completions/"
        if test -f "$COMPLETION_PATH/git-prompt.sh"
        then
                . "$COMPLETION_PATH/git"
                . "$COMPLETION_PATH/git-prompt.sh"
                PS1="$PS1"'\[\033[36m\]'  # change color to cyan
                PS1="$PS1"'`__git_ps1`'   # bash function
        fi
fi
PS1="$PS1"'\[\033[0m\]'        # change color
PS1="$PS1"'\n'                 # new line
PS1="$PS1"'$ '                 # prompt: always $