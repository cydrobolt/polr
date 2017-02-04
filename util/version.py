import re, datetime, string, random

print "> New version name (e.g 2.0.0):"
new_version = raw_input()

print "> Is this a stable release? [y, n]"
is_stable = raw_input()

with open('.env.setup', 'r+') as setup_env:
    setup_env_lines = setup_env.read()
    now = datetime.datetime.now()

    new_setup_key = ''.join(random.SystemRandom().choice(string.ascii_letters + string.digits + string.punctuation) for _ in range(32))

    # Update setup key
    setup_env_lines = re.sub(r'(?is)APP_KEY=[^\n]+', 'APP_KEY={}'.format(new_setup_key), setup_env_lines)
    # Update date and release in setup env
    setup_env_lines = re.sub(r'cVERSION=[0-9a-zA-Z\.]+', 'VERSION={}'.format(new_version), setup_env_lines)
    setup_env_lines = re.sub(r'(?is)VERSION_RELMONTH=\w+', 'VERSION_RELMONTH={}'.format(now.strftime('%B')), setup_env_lines)
    setup_env_lines = re.sub(r'(?is)VERSION_RELDAY=\w+', 'VERSION_RELDAY={}'.format(now.day), setup_env_lines)
    setup_env_lines = re.sub(r'(?is)VERSION_RELYEAR=\w+', 'VERSION_RELYEAR={}'.format(now.year), setup_env_lines)

    # Overwite existing file
    setup_env.seek(0)
    setup_env.write(setup_env_lines)
    setup_env.truncate()

if is_stable == 'y':
    with open('docs/index.md', 'r+') as index_md:
        index_md_lines = index_md.read()
        index_md_lines = re.sub(r'(?is)stable-[0-9\.]+-green.svg', 'stable-{}-green.svg'.format(new_version), index_md_lines)

        # Overwite existing file
        index_md.seek(0)
        index_md.write(index_md_lines)
        index_md.truncate()

print "Done!"
