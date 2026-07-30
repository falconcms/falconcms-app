<?php

namespace FalconSlider\Support;

/**
 * A large curated set of Material Symbols (Outlined) icon names for the layer icon
 * picker. The editor loads a Google-Fonts subset of EXACTLY these names (see
 * editor.blade.php) so every icon here renders in the picker; the frontend then loads
 * only the icons a slider actually uses (see frontend/render.blade.php).
 */
class IconLibrary
{
    /** @return array<int,string> flat, de-duplicated list of Material Symbols names */
    public static function names(): array
    {
        return array_values(array_unique([
            // ---- Popular / UI / actions ----
            'star', 'star_border', 'star_half', 'star_rate', 'stars', 'grade',
            'favorite', 'favorite_border', 'thumb_up', 'thumb_down', 'recommend',
            'check', 'check_circle', 'done', 'done_all', 'task_alt', 'verified', 'verified_user',
            'bolt', 'rocket_launch', 'rocket', 'emoji_events', 'workspace_premium', 'military_tech',
            'diamond', 'celebration', 'lightbulb', 'tips_and_updates', 'auto_awesome', 'auto_fix_high',
            'local_fire_department', 'whatshot', 'trending_up', 'trending_down', 'trending_flat',
            'flag', 'outlined_flag', 'tour', 'bookmark', 'bookmark_border', 'bookmarks', 'label',
            'new_releases', 'sell', 'loyalty', 'thumbs_up_down', 'sentiment_satisfied',
            'add', 'remove', 'close', 'clear', 'menu', 'more_vert', 'more_horiz', 'apps', 'grid_view',
            'search', 'manage_search', 'settings', 'tune', 'filter_list', 'filter_alt', 'sort',
            'home', 'dashboard', 'widgets', 'category', 'extension', 'palette', 'brush', 'colorize',
            'edit', 'edit_note', 'draw', 'create', 'delete', 'delete_forever', 'restore_from_trash',
            'content_copy', 'content_paste', 'content_cut', 'save', 'save_as', 'print', 'share',
            'reply', 'reply_all', 'forward', 'undo', 'redo', 'refresh', 'sync', 'cached', 'autorenew',
            'open_in_new', 'open_in_full', 'fullscreen', 'fullscreen_exit', 'zoom_in', 'zoom_out',
            'add_circle', 'remove_circle', 'cancel', 'block', 'report', 'do_not_disturb_on',
            'warning', 'error', 'info', 'help', 'help_outline', 'priority_high', 'question_mark',
            'notifications', 'notifications_active', 'notifications_none', 'campaign', 'feedback',
            'rate_review', 'reviews', 'touch_app', 'ads_click', 'ad_units', 'push_pin', 'pin_drop',
            'toggle_on', 'toggle_off', 'power_settings_new', 'exit_to_app', 'launch',

            // ---- Navigation / arrows ----
            'arrow_forward', 'arrow_back', 'arrow_upward', 'arrow_downward',
            'arrow_forward_ios', 'arrow_back_ios', 'arrow_right_alt', 'arrow_left', 'arrow_right',
            'arrow_drop_down', 'arrow_drop_up', 'arrow_circle_right', 'arrow_circle_left',
            'arrow_circle_up', 'arrow_circle_down', 'chevron_left', 'chevron_right',
            'expand_more', 'expand_less', 'unfold_more', 'unfold_less', 'double_arrow',
            'north', 'south', 'east', 'west', 'north_east', 'north_west', 'south_east', 'south_west',
            'first_page', 'last_page', 'keyboard_arrow_down', 'keyboard_arrow_up',
            'keyboard_arrow_left', 'keyboard_arrow_right', 'keyboard_double_arrow_right',
            'keyboard_double_arrow_down', 'swap_horiz', 'swap_vert', 'sync_alt', 'compare_arrows',
            'call_made', 'call_received', 'low_priority', 'subdirectory_arrow_right', 'moving',

            // ---- Commerce / finance ----
            'shopping_cart', 'add_shopping_cart', 'remove_shopping_cart', 'shopping_cart_checkout',
            'shopping_bag', 'shopping_basket', 'storefront', 'store', 'add_business', 'local_mall',
            'local_offer', 'percent', 'redeem', 'card_giftcard',
            'payments', 'payment', 'credit_card', 'credit_score', 'account_balance',
            'account_balance_wallet', 'wallet', 'savings', 'attach_money', 'money', 'paid',
            'monetization_on', 'price_check', 'price_change', 'request_quote', 'receipt',
            'receipt_long', 'point_of_sale', 'local_atm', 'currency_exchange',
            'qr_code', 'qr_code_2', 'qr_code_scanner', 'barcode_reader', 'inventory', 'inventory_2',

            // ---- Communication / contact ----
            'call', 'call_end', 'phone', 'phone_in_talk', 'phone_enabled', 'phone_iphone',
            'phone_android', 'mail', 'mail_outline', 'email', 'alternate_email', 'contact_mail',
            'contact_phone', 'contacts', 'contact_page', 'perm_contact_calendar',
            'place', 'location_on', 'location_city', 'my_location', 'near_me',
            'map', 'explore', 'navigation', 'directions', 'support_agent', 'headset_mic',
            'headphones', 'connect_without_contact', 'hub', 'rss_feed', 'podcasts', 'cell_tower',
            'send', 'mark_email_read', 'mark_email_unread', 'drafts', 'inbox',
            'chat', 'chat_bubble', 'forum', 'comment', 'sms', 'mode_comment', 'quickreply',
            'question_answer', 'record_voice_over', 'voice_chat', 'duo',

            // ---- People / social ----
            'public', 'language', 'translate', 'group', 'groups', 'groups_2', 'groups_3',
            'person', 'person_add', 'person_remove', 'people', 'people_alt', 'person_outline',
            'account_circle', 'supervisor_account', 'manage_accounts', 'badge', 'face',
            'sentiment_very_satisfied', 'sentiment_dissatisfied', 'mood', 'mood_bad',
            'emoji_emotions', 'emoji_people', 'emoji_nature', 'emoji_objects', 'emoji_food_beverage',
            'emoji_transportation', 'waving_hand', 'back_hand', 'front_hand', 'handshake',
            'volunteer_activism', 'diversity_1', 'diversity_2', 'diversity_3',
            'psychology', 'psychology_alt', 'self_improvement', 'elderly', 'pregnant_woman',
            'child_care', 'accessible', 'accessibility_new', 'sign_language',

            // ---- Media ----
            'play_arrow', 'play_circle', 'pause', 'pause_circle', 'stop', 'stop_circle',
            'skip_next', 'skip_previous', 'fast_forward', 'fast_rewind', 'replay', 'shuffle',
            'repeat', 'repeat_one', 'queue_music', 'library_music', 'music_note', 'music_off',
            'audiotrack', 'album', 'mic', 'mic_off', 'mic_none', 'volume_up', 'volume_down',
            'volume_off', 'volume_mute', 'graphic_eq', 'equalizer', 'radio', 'movie', 'theaters',
            'live_tv', 'ondemand_video', 'video_library', 'videocam', 'videocam_off', 'video_call',
            'slideshow', 'subscriptions', 'cast', 'cast_connected', 'airplay', 'hd', '4k',
            'high_quality', 'closed_caption', 'subtitles', 'camera', 'camera_alt', 'photo_camera',
            'photo', 'image', 'panorama', 'collections', 'photo_library', 'add_a_photo',
            'image_search', 'wallpaper', 'filter', 'crop', 'crop_free', 'rotate_left', 'rotate_right',
            'flip', 'straighten', 'exposure', 'gradient', 'blur_on', 'flare', 'format_paint',

            // ---- Business / office / education ----
            'business', 'business_center', 'work', 'work_outline', 'corporate_fare', 'apartment',
            'domain', 'meeting_room', 'cases', 'school', 'menu_book', 'book', 'library_books',
            'auto_stories', 'history_edu', 'science', 'biotech', 'calculate', 'functions',
            'engineering', 'architecture', 'design_services', 'construction', 'handyman', 'build',
            'plumbing', 'carpenter', 'precision_manufacturing', 'factory', 'warehouse',
            'assignment', 'assignment_turned_in', 'assignment_ind', 'task', 'fact_check',
            'checklist', 'rule', 'gavel', 'balance', 'policy', 'description', 'article', 'note',
            'sticky_note_2', 'subject', 'notes', 'feed', 'newspaper', 'contact_support', 'quiz',
            'help_center', 'live_help', 'model_training',

            // ---- Time / calendar / data ----
            'schedule', 'access_time', 'timer', 'timer_off', 'hourglass_empty', 'hourglass_full',
            'hourglass_top', 'hourglass_bottom', 'av_timer', 'alarm', 'alarm_on', 'alarm_add',
            'snooze', 'watch_later', 'update', 'history', 'restore', 'event', 'event_available',
            'event_busy', 'calendar_today', 'calendar_month', 'date_range', 'today', 'edit_calendar',
            'pending', 'pending_actions', 'bar_chart', 'stacked_bar_chart', 'show_chart',
            'insert_chart', 'bubble_chart', 'pie_chart', 'donut_large', 'analytics', 'insights',
            'monitoring', 'query_stats', 'leaderboard', 'data_usage', 'timeline', 'assessment',
            'table_chart', 'grid_on',

            // ---- Food / health / lifestyle ----
            'restaurant', 'restaurant_menu', 'local_dining', 'fastfood', 'lunch_dining',
            'dinner_dining', 'breakfast_dining', 'brunch_dining', 'ramen_dining', 'rice_bowl',
            'bakery_dining', 'icecream', 'cake', 'local_cafe', 'local_bar', 'coffee', 'wine_bar',
            'liquor', 'sports_bar', 'nightlife', 'kitchen', 'blender', 'tapas', 'set_meal', 'egg',
            'cookie', 'local_pizza', 'kebab_dining', 'fitness_center', 'spa', 'hot_tub', 'pool',
            'sports_soccer', 'sports_basketball', 'sports_tennis', 'sports_football',
            'sports_esports', 'sports_gymnastics', 'sports_martial_arts', 'sports_score',
            'directions_run', 'directions_bike', 'directions_walk', 'hiking', 'downhill_skiing',
            'surfing', 'skateboarding', 'sailing', 'kayaking', 'scuba_diving',
            'medical_services', 'local_hospital', 'local_pharmacy', 'medication', 'vaccines',
            'healing', 'health_and_safety', 'monitor_heart', 'bloodtype', 'personal_injury',
            'emergency', 'masks', 'sanitizer', 'clean_hands', 'ecg_heart',

            // ---- Nature / weather / eco ----
            'eco', 'park', 'forest', 'grass', 'nature', 'nature_people', 'local_florist', 'yard',
            'potted_plant', 'compost', 'recycling', 'energy_savings_leaf', 'solar_power',
            'wind_power', 'water_drop', 'water', 'waves', 'tsunami', 'air', 'cloud', 'cloud_queue',
            'sunny', 'partly_cloudy_day', 'cloudy', 'rainy', 'thunderstorm', 'snowing', 'ac_unit',
            'severe_cold', 'foggy', 'dark_mode', 'light_mode', 'nights_stay', 'bedtime',
            'brightness_high', 'brightness_low', 'wb_twilight', 'thermostat', 'umbrella',
            'beach_access', 'terrain', 'landscape', 'volcano', 'agriculture', 'pets',
            'cruelty_free', 'bug_report', 'storm', 'mode_night',

            // ---- Travel / transport / places ----
            'flight', 'flight_takeoff', 'flight_land', 'travel_explore', 'luggage', 'hotel',
            'king_bed', 'room_service', 'local_taxi', 'local_shipping', 'directions_car',
            'directions_bus', 'directions_boat', 'directions_subway', 'directions_railway', 'train',
            'tram', 'subway', 'two_wheeler', 'pedal_bike', 'electric_scooter', 'electric_car',
            'electric_bike', 'local_gas_station', 'ev_station', 'car_rental', 'car_repair',
            'commute', 'moped', 'anchor', 'signpost', 'festival', 'attractions', 'castle', 'church',
            'mosque', 'synagogue', 'temple_buddhist', 'temple_hindu', 'stadium', 'museum',
            'theater_comedy', 'cottage', 'house', 'home_work', 'villa', 'cabin', 'holiday_village',
            'camping', 'local_activity', 'confirmation_number',

            // ---- Devices / tech ----
            'computer', 'laptop', 'desktop_windows', 'desktop_mac', 'tv', 'tablet', 'tablet_mac',
            'smartphone', 'watch', 'memory', 'developer_board', 'sim_card', 'sd_card', 'storage',
            'cloud_upload', 'cloud_download', 'cloud_done', 'cloud_sync', 'backup', 'router',
            'wifi', 'wifi_off', 'bluetooth', 'cable', 'keyboard', 'mouse', 'headset', 'speaker',
            'scanner', 'videogame_asset', 'devices', 'device_hub', 'dns', 'code', 'data_object',
            'integration_instructions', 'api', 'webhook', 'terminal', 'adb', 'battery_full',
            'battery_charging_full', 'power', 'electrical_services', 'usb', 'settings_ethernet',
            'wifi_tethering', 'signal_cellular_alt', 'network_check', 'lan',

            // ---- Files / formatting / layout ----
            'folder', 'folder_open', 'create_new_folder', 'folder_shared', 'drive_file_move',
            'upload_file', 'file_download', 'file_upload', 'download', 'upload', 'picture_as_pdf',
            'insert_drive_file', 'note_add', 'attach_file', 'attachment', 'link', 'link_off',
            'add_link', 'archive', 'unarchive', 'file_copy', 'text_snippet', 'source',
            'format_align_left', 'format_align_center', 'format_align_right', 'format_align_justify',
            'format_bold', 'format_italic', 'format_underlined', 'format_color_text', 'format_size',
            'format_list_bulleted', 'format_list_numbered', 'format_quote', 'table_rows',
            'view_column', 'view_list', 'view_module', 'view_carousel', 'view_agenda', 'splitscreen',

            // ---- Security ----
            'lock', 'lock_open', 'lock_reset', 'key', 'password', 'visibility', 'visibility_off',
            'fingerprint', 'admin_panel_settings', 'shield', 'security', 'gpp_good', 'vpn_key',
            'vpn_lock', 'enhanced_encryption', 'no_encryption', 'privacy_tip',
        ]));
    }
}
