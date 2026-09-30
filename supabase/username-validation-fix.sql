alter table public.ss_profiles drop constraint ss_profiles_username_check;
alter table public.ss_profiles add constraint ss_profiles_username_check check (length(username) between 3 and 80 and translate(username,'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_.-','')='');
