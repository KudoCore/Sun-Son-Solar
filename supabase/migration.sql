-- Additive integration. Existing project tables, policies and triggers are untouched.
create table public.ss_profiles (
 id uuid primary key references auth.users(id), username text unique not null check(username ~ '^[a-Za-z0-9_.-]{3,80}$'),
 first_name text not null, last_name text not null, middle_name text, birthday date, gender text,
 email text, phone text, address text, department text not null,
 role text not null default 'employee' check(role in ('employee','ceo','it_head')),
 approved boolean not null default false, must_change_password boolean not null default false,
 session_version integer not null default 1, created_at timestamptz not null default now()
);
create unique index ss_username_lower on public.ss_profiles(lower(username));
create table public.ss_enquiries (
 id bigint generated always as identity primary key, reference text unique not null default ('SS'||upper(substr(replace(gen_random_uuid()::text,'-',''),1,14))),
 name text not null,email text not null,phone text,interest text not null,message text not null,consent_version text not null default '2026-09-30',created_at timestamptz not null default now()
);
create table public.ss_attendance (
 id bigint generated always as identity primary key,user_id uuid not null references public.ss_profiles(id),
 checked_at timestamptz not null default now(),work_date date not null default ((now() at time zone 'Asia/Manila')::date),
 latitude double precision not null check(latitude between -90 and 90),longitude double precision not null check(longitude between -180 and 180),accuracy double precision not null check(accuracy>=0),
 unique(user_id,work_date)
);
create table public.ss_settings(id boolean primary key default true check(id),address text not null default '',phone text not null default '',maps_url text not null default '',response_time text not null default '',ga_id text not null default '',hr_details_enabled boolean not null default false,site_latitude double precision,site_longitude double precision,site_radius_m integer not null default 150 check(site_radius_m>0),max_accuracy_m integer not null default 100 check(max_accuracy_m>0));
insert into public.ss_settings(id) values(true);
create table public.ss_rate_limits(bucket text primary key,attempts integer not null,started_at timestamptz not null default now());
create table public.ss_audit(id bigint generated always as identity primary key,actor uuid,action text not null,target uuid,created_at timestamptz not null default now());
create table public.ss_bootstrap(token_hash text primary key,expires_at timestamptz not null,consumed boolean not null default false);
alter table public.ss_profiles enable row level security;
alter table public.ss_enquiries enable row level security;
alter table public.ss_attendance enable row level security;
alter table public.ss_settings enable row level security;
alter table public.ss_rate_limits enable row level security;
alter table public.ss_audit enable row level security;
alter table public.ss_bootstrap enable row level security;
-- Only the server API may access these new tables. No public policies are created.
revoke all on public.ss_profiles,public.ss_enquiries,public.ss_attendance,public.ss_settings,public.ss_rate_limits,public.ss_audit,public.ss_bootstrap from anon,authenticated;
grant all on public.ss_profiles,public.ss_enquiries,public.ss_attendance,public.ss_settings,public.ss_rate_limits,public.ss_audit,public.ss_bootstrap to service_role;
create function public.ss_rate_limit(p_bucket text,p_limit integer,p_seconds integer) returns boolean language plpgsql security definer set search_path='' as $$
declare n integer;
begin
 insert into public.ss_rate_limits(bucket,attempts,started_at) values(p_bucket,1,now())
 on conflict(bucket) do update set attempts=case when public.ss_rate_limits.started_at < now()-make_interval(secs=>p_seconds) then 1 else public.ss_rate_limits.attempts+1 end,
 started_at=case when public.ss_rate_limits.started_at < now()-make_interval(secs=>p_seconds) then now() else public.ss_rate_limits.started_at end returning attempts into n;
 return n<=p_limit;
end $$;
revoke all on function public.ss_rate_limit(text,integer,integer) from public,anon,authenticated;
grant execute on function public.ss_rate_limit(text,integer,integer) to service_role;
